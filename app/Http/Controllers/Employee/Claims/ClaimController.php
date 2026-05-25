<?php

namespace App\Http\Controllers\Employee\Claims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services\Claim;
use App\Models\Membership\Member;
use App\Models\Membership\Attachment;
use App\Models\System\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Exports\ClaimsExport;
use Maatwebsite\Excel\Facades\Excel;

class ClaimController extends Controller
{
    /**
     * Display a listing of all claims.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $claims = $query->paginate(10)->withQueryString();
        $claimTypes = Claim::CLAIM_TYPES;

        // Statistics for cards
        $paidCount = Claim::where('status', 'paid')->count(); // تم صرفها
        $pendingApprovalCount = Claim::where('status', 'pending')->count(); // بانتظار الأعتماد
        $pendingSettlementCount = Claim::where('status', 'approved')->count(); // بانتظار التسوية

        return view('employee.claims.index', compact('claims', 'claimTypes', 'paidCount', 'pendingApprovalCount', 'pendingSettlementCount'));
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Claim::with(['membership.member'])->latest();

        // Search by member name, membership number or claim id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('membership', function ($q2) use ($search) {
                      $q2->where('membership_number', 'LIKE', "%{$search}%")
                         ->orWhereHas('member', function ($q3) use ($search) {
                             $q3->where('name', 'LIKE', "%{$search}%");
                         });
                  });
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            // Expected format from JS might be d/m/Y or Y-m-d. Let's parse it safely.
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                // If the format is already Y-m-d or different
                $query->whereDate('created_at', $request->date);
            }
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        return $query;
    }

    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        return Excel::download(new ClaimsExport($query), 'claims.xlsx');
    }

    /**
     * Store a newly created claim in storage.
     */
    public function store(Request $request, $memberId)
    {
        $member = Member::with('membershipInfo')->findOrFail($memberId);

        if (!$member->membershipInfo) {
            return redirect()
                ->route('members.show', $member->id)
                ->with('error', 'لا يوجد عضوية مسجلة لهذا العضو.');
        }

        $validated = $request->validate([
            'claim_type'       => ['required', 'string', 'in:' . implode(',', array_keys(Claim::CLAIM_TYPES))],
            'has_minors'       => ['nullable', 'boolean'],
            'claim_documents'  => ['nullable', 'array'],
            'claim_documents.*'=> ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($validated['claim_type'] === 'death' && $request->input('has_minors') == 1) {
            $request->validate([
                'claim_documents.minors_birth_certificates' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
                'claim_documents.guardianship_decision'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            ], [
                'claim_documents.minors_birth_certificates.required' => 'شهادات ميلاد القصر مطلوبة.',
                'claim_documents.guardianship_decision.required' => 'قرار الوصاية مطلوب.',
            ]);
        }

        $claim = DB::transaction(function () use ($request, $validated, $member) {
            $claim = Claim::create([
                'membership_id'      => $member->membershipInfo->id,
                'type'               => $validated['claim_type'],
                'amount'             => 0, // Amount to be determined by admin during approval
                'status'             => 'pending',
                'attachment_receipt'  => null,
            ]);

            // Store claim documents as member attachments with claim-specific types
            if ($request->hasFile('claim_documents')) {
                foreach ($request->file('claim_documents') as $docType => $file) {
                    $path = $file->store("members/{$member->id}/claims/{$claim->id}", 'public');
                    Attachment::create([
                        'member_id' => $member->id,
                        'type'      => "claim_{$claim->id}_{$docType}",
                        'file_path' => $path,
                    ]);
                }
            }

            // Audit log
            $this->logAudit('create', 'claims', $claim->id, null, $claim->toArray());

            return $claim;
        });

        return redirect()
            ->route('members.show', ['member' => $member->id, 'tab' => 'claims'])
            ->with('success', 'تم تسجيل المطالبة بنجاح');
    }

    /**
     * Display the specified claim to approve it.
     */
    public function show(Claim $claim)
    {
        // Load relationships needed for the view
        $claim->load(['membership.member.employmentInfo', 'membership.member.department']);

        $claimTypes = Claim::CLAIM_TYPES;

        return view('employee.claims.show', compact('claim', 'claimTypes'));
    }

    /**
     * Approve the specified claim.
     */
    public function approve(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'amount'         => ['nullable', 'numeric', 'min:0'], // Optional: If admin decides final amount here
        ]);

        $oldValues = $claim->toArray();

        DB::transaction(function () use ($request, $validated, $claim, $oldValues) {

            $updateData = [
                'status' => 'approved',
                'attachment_receipt' => $validated['receipt_number'], // Using this field for receipt number or we can add receipt_number to DB
            ];

            if (isset($validated['amount'])) {
                $updateData['amount'] = $validated['amount'];
            }

            if ($request->hasFile('receipt_file')) {
                $memberId = $claim->membership->member_id;
                $path = $request->file('receipt_file')->store("members/{$memberId}/claims/{$claim->id}", 'public');

                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "claim_{$claim->id}_approval_receipt",
                    'file_path' => $path,
                ]);
            }

            $claim->update($updateData);

            // Audit log
            $this->logAudit('approve', 'claims', $claim->id, $oldValues, $claim->fresh()->toArray());
        });

        return redirect()->route('claims.index')->with('success', 'تم اعتماد المطالبة بنجاح.');
    }

    /**
     * Create an audit log entry for any action.
     */
    private function logAudit(string $action, string $tableName, int $recordId, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }
}
