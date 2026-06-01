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
use App\Services\ClaimCalculationService;

class ClaimController extends Controller
{
    protected $claimCalculationService;

    public function __construct(ClaimCalculationService $claimCalculationService)
    {
        $this->claimCalculationService = $claimCalculationService;
    }
    /**
     * Display a listing of all claims.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $claims = $query->paginate(10)->withQueryString();
        $claimTypes = Claim::CLAIM_TYPES;

        // Gather statistics for the top summary cards on the claims dashboard.
        $paidCount = Claim::where('status', 'paid')->count(); // Claims that have been paid out
        $pendingApprovalCount = Claim::where('status', 'pending')->count(); // Claims awaiting administrative approval
        $pendingSettlementCount = Claim::where('status', 'approved')->count(); // Claims approved but awaiting final settlement

        return view('employee.claims.index', compact('claims', 'claimTypes', 'paidCount', 'pendingApprovalCount', 'pendingSettlementCount'));
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Claim::with(['membership.member'])->latest();

        // Allow searching claims by member name, membership number, or the specific claim ID.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('membership', function ($q2) use ($search) {
                      $q2->where('membership_number', 'LIKE', "%{$search}%")
                         ->orWhereHas('member.user', function ($q3) use ($search) {
                             $q3->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('national_id', 'LIKE', "%{$search}%");
                         });
                  });
            });
        }

        // Apply date filtering if a specific date is provided.
        if ($request->filled('date')) {
            // The frontend might send dates in 'd/m/Y' format. We parse it safely to standard 'Y-m-d' for the database.
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                // Fallback in case the date is already in 'Y-m-d' format.
                $query->whereDate('created_at', $request->date);
            }
        }

        // Apply status filtering (e.g., pending, approved, paid).
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply type filtering based on the claim type (e.g., retirement, death).
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

        if ($member->membershipInfo->claims()->exists()) {
            return redirect()
                ->route('members.show', $member->id)
                ->with('error', 'يوجد مطالبة مسجلة مسبقاً لهذا العضو.');
        }

        $forbiddenStatuses = ['withdrawn', 'dismissed', 'suspended'];
        if (in_array($member->membershipInfo->status, $forbiddenStatuses)) {
            return redirect()
                ->route('members.show', $member->id)
                ->with('error', 'حالة العضوية الحالية لا تسمح بإنشاء مطالبة.');
        }

        $validated = $request->validate([
            'claim_type'       => ['required', 'string', 'in:' . implode(',', array_keys(Claim::CLAIM_TYPES))],
            'has_minors'       => ['nullable', 'boolean'],
            'claim_documents'  => ['nullable', 'array'],
            'claim_documents.*'=> ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($validated['claim_type'] === 'retirement') {
            $retirementAge = (int) \App\Models\System\SystemSetting::get('retirement_age', 60);
            if ($member->birth_date && \Carbon\Carbon::parse($member->birth_date)->age < $retirementAge) {
                return redirect()->back()->with('error', "لا يمكن إنشاء مطالبة تقاعد لأن العضو لم يبلغ سن التقاعد ($retirementAge عاماً).")->withInput();
            }
        }

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
            $tempClaim = new Claim([
                'membership_id' => $member->membershipInfo->id,
                'type'          => $validated['claim_type'],
            ]);
            $tempClaim->setRelation('membership', $member->membershipInfo->load(['subscriptions', 'member.employmentInfo', 'loans.installments']));
            
            $calculations = $this->claimCalculationService->calculate($tempClaim);

            $claim = Claim::create([
                'membership_id'      => $member->membershipInfo->id,
                'type'               => $validated['claim_type'],
                'amount'             => max(0, $calculations['net_amount']), // The initial calculated amount
                'status'             => 'pending',
                'attachment_receipt'  => null,
            ]);

            // Save the uploaded claim documents as attachments linked to the member's profile.
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

            // Log the creation of this new claim for auditing purposes.
            $this->logAudit('create', 'claims', $claim->id, null, $claim->toArray());

            $user = $member->user ?? null;
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'تم تسجيل المطالبة بنجاح',
                    'message' => 'لقد تم تقديم المطالبة الخاصة بك وهي الآن قيد المراجعة.',
                ]);
            }

            $admins = \App\Models\Auth\User::whereHas('role', function($q) {
                $q->where('name', 'Admin');
            })->orWhereJsonContains('custom_permissions', 'إدارة المطالبات')->get();
            foreach ($admins as $admin) {
                if ($admin->id !== auth()->id()) {
                    \App\Models\Auth\Notification::create([
                        'user_id' => $admin->id,
                        'title'   => 'تسجيل مطالبة جديدة',
                        'message' => 'تم تسجيل مطالبة جديدة للعضو ' . ($user ? $user->name : 'غير معروف') . ' وبانتظار الاعتماد.',
                    ]);
                }
            }

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
        // Load all related models required to render the claim details view accurately.
        $claim->load(['membership.member.employmentInfo', 'membership.member.department', 'membership.subscriptions', 'membership.loans.installments']);

        $claimTypes = Claim::CLAIM_TYPES;
        
        $calculations = $this->claimCalculationService->calculate($claim);

        return view('employee.claims.show', array_merge(
            compact('claim', 'claimTypes'),
            $calculations
        ));
    }

    /**
     * Approve the specified claim.
     */
    public function approve(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_file'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
            'amount'         => ['nullable', 'numeric', 'min:0'], // Allow the admin to optionally override and specify the final approved amount.
        ]);

        $oldValues = $claim->toArray();

        DB::transaction(function () use ($request, $validated, $claim, $oldValues) {

            $updateData = [
                'status' => 'approved',
            ];

            if (isset($validated['amount'])) {
                $updateData['amount'] = $validated['amount'];
            } else {
                $claim->load(['membership.member.employmentInfo', 'membership.subscriptions', 'membership.loans.installments']);
                $calculations = $this->claimCalculationService->calculate($claim);
                $updateData['amount'] = $calculations['net_amount'];
            }

            $attachmentPath = null;
            if ($request->hasFile('receipt_file')) {
                $memberId = $claim->membership->member_id;
                $attachmentPath = $request->file('receipt_file')->store("members/{$memberId}/claims/{$claim->id}", 'public');

                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "claim_{$claim->id}_approval_receipt",
                    'file_path' => $attachmentPath,
                ]);
            }

            $claim->update($updateData);

            // Close the active loan and mark unpaid installments as paid since they were deducted
            $activeLoan = $claim->membership->loans()->where('status', 'active')->first();
            if ($activeLoan) {
                $activeLoan->update(['status' => 'completed']);
                $activeLoan->installments()->whereIn('status', ['unpaid', 'overdue'])->update([
                    'status' => 'paid',
                ]);
            }

            // Also mark deducted overdue subscriptions as paid
            $claim->membership->subscriptions()
                ->whereIn('status', ['unpaid', 'overdue'])
                ->where('due_date', '<=', \Carbon\Carbon::now())
                ->update(['status' => 'paid']);

            $membershipStatusMapping = [
                'retirement'              => 'pension_eligible',
                'early_retirement'        => 'withdrawn',
                'resignation'             => 'withdrawn',
                'withdrawal'              => 'withdrawn',
                'expulsion'               => 'dismissed',
                'professional_disability' => 'membership_expired',
                'transfer'                => 'withdrawn',
                'death'                   => 'membership_expired',
            ];

            if (isset($membershipStatusMapping[$claim->type])) {
                $claim->membership()->update([
                    'status' => $membershipStatusMapping[$claim->type]
                ]);
            }

            \App\Models\Financial\Transaction::create([
                'membership_id'   => $claim->membership_id,
                'reference_type'  => \App\Models\Services\Claim::class,
                'reference_id'    => $claim->id,
                'amount'          => $updateData['amount'],
                'type'            => \App\Models\Financial\Transaction::TYPE_OUT,
                'method'          => 'check', 
                'category'        => 'claim_payment',
                'description'     => 'صرف مستحقات المطالبة',
                'receipt_no'      => $validated['receipt_number'],
                'attachment_path' => $attachmentPath,
                'created_by'      => auth()->id(),
            ]);

            $user = $claim->membership->member->user ?? null;
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'اعتماد مطالبة صرف مستحقات',
                    'message' => 'تم اعتماد المطالبة الخاصة بك بنجاح، يرجى التوجه لإدارة الصندوق لاستلام الشيك.',
                ]);

                if ($user->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ClaimApprovedMail($claim));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send claim approved mail: ' . $e->getMessage());
                    }
                }
            }

            // Log the approval action.
            $this->logAudit('approve', 'claims', $claim->id, $oldValues, $claim->fresh()->toArray());
        });

        return redirect()->route('claims.index')->with('success', 'تم اعتماد المطالبة بنجاح.');
    }

    /**
     * Create an audit log entry for any action.
     */

    /**
     * Finalize the claim (Upload signed receipt for cheque payment).
     */
    public function finalize(Request $request, Claim $claim)
    {
        $request->validate([
            'signed_receipt' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $oldValues = $claim->toArray();

        DB::transaction(function () use ($request, $claim, $oldValues) {
            $memberId = $claim->membership->member_id;
            $path = $request->file('signed_receipt')->store("members/{$memberId}/claims/{$claim->id}", 'public');

            Attachment::create([
                'member_id' => $memberId,
                'type'      => "claim_{$claim->id}_signed_receipt",
                'file_path' => $path,
            ]);

            $claim->update([
                'status' => 'paid', // Mark the claim as delivered (paid) to the member.
                'attachment_receipt' => $path
            ]);

            $user = $claim->membership->member->user ?? null;
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'تسليم مستحقات المطالبة',
                    'message' => 'تم تسليمك شيك المطالبة الخاص بك بنجاح.',
                ]);
            }

            $this->logAudit('finalize', 'claims', $claim->id, $oldValues, $claim->fresh()->toArray());
        });
        return redirect()
            ->route('members.show', ['member' => $claim->membership->member_id, 'tab' => 'claims'])
            ->with('success', 'تم رفع الإقرار الموقع ودفع الشيك بنجاح.');
    }

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
