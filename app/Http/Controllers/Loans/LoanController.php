<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Membership\Member;
use App\Models\Membership\Attachment;
use App\Models\System\AuditLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of all loans with search, filters, and statistics.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['membership.member', 'installments'])->latest();

        // Search by member name, national ID, membership number or loan id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('membership', function ($q2) use ($search) {
                      $q2->where('membership_number', 'LIKE', "%{$search}%")
                         ->orWhereHas('member', function ($q3) use ($search) {
                             $q3->where('full_name', 'LIKE', "%{$search}%")
                                ->orWhere('national_id', 'LIKE', "%{$search}%");
                         });
                  });
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                $query->whereDate('created_at', $request->date);
            }
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $status = $request->status;

            if ($status === 'overdue') {
                // Loans that have overdue installments
                $query->whereHas('installments', function ($q) {
                    $q->where('status', 'overdue');
                });
            } else {
                $query->where('status', $status);
            }
        }

        $loans = $query->paginate(10)->withQueryString();

        // ── Statistics cards ──
        // قروض مُفعلة هذا الشهر
        $activeLoansThisMonth = Loan::where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // طلبات قروض تحت المراجعة
        $pendingLoansCount = Loan::where('status', 'pending')->count();

        // أقساط متأخره اليوم
        $overdueInstallmentsCount = Installment::where('status', 'overdue')
            ->whereDate('due_date', '<=', now()->toDateString())
            ->count();

        return view('loans.index', compact(
            'loans',
            'activeLoansThisMonth',
            'pendingLoansCount',
            'overdueInstallmentsCount'
        ));
    }

    /**
     * Store a newly created loan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'    => ['required', 'exists:members,id'],
            'total_amount' => ['required', 'numeric', 'in:5000,10000,20000'],
            'months'       => ['required', 'integer', 'in:12,24,32'],
        ]);

        $member = Member::with('membershipInfo.loans')->findOrFail($validated['member_id']);

        if (!$member->membershipInfo) {
            return redirect()->route('loans.index')
                ->with('error', 'لا يوجد عضوية مسجلة لهذا العضو.');
        }

        // Business rule: only 1 active loan per member
        $hasActiveLoan = $member->membershipInfo->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('loans.index')
                ->with('error', 'يوجد قرض نشط بالفعل لهذا العضو. لا يمكن إنشاء قرض جديد.');
        }

        $installmentAmount = round($validated['total_amount'] / $validated['months'], 2);

        $loan = DB::transaction(function () use ($validated, $member, $installmentAmount) {
            $loan = Loan::create([
                'membership_id'      => $member->membershipInfo->id,
                'total_amount'       => $validated['total_amount'],
                'months'             => $validated['months'],
                'installment_amount' => $installmentAmount,
                'status'             => 'pending',
            ]);

            // Generate installment schedule
            for ($i = 1; $i <= $validated['months']; $i++) {
                Installment::create([
                    'loan_id'  => $loan->id,
                    'amount'   => $installmentAmount,
                    'due_date' => now()->addMonths($i)->startOfMonth(),
                    'status'   => 'unpaid',
                ]);
            }

            // Audit log
            $this->logAudit('create', 'loans', $loan->id, null, $loan->toArray());

            return $loan;
        });

        return redirect()->route('loans.index')
            ->with('success', 'تم إنشاء طلب القرض بنجاح.');
    }

    /**
     * Display loan details.
     */
    public function show(Loan $loan)
    {
        $loan->load(['membership.member', 'installments' => function ($q) {
            $q->orderBy('due_date');
        }]);

        return view('loans.show', compact('loan'));
    }

    /**
     * Record an installment payment.
     */
    public function recordPayment(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'installment_ids'   => ['required', 'array', 'min:1'],
            'installment_ids.*' => ['required', 'exists:installments,id'],
            'receipt_number'    => ['required', 'string', 'max:255'],
            'receipt_file'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $loan->load('membership');

        $oldValues = $loan->toArray();

        DB::transaction(function () use ($request, $validated, $loan) {
            // Mark selected installments as paid
            Installment::whereIn('id', $validated['installment_ids'])
                ->where('loan_id', $loan->id)
                ->update([
                    'status'     => 'paid',
                    'updated_at' => now(),
                ]);

            // Upload receipt file if provided
            if ($request->hasFile('receipt_file')) {
                $memberId = $loan->membership->member_id;
                $path = $request->file('receipt_file')
                    ->store("members/{$memberId}/loans/{$loan->id}", 'public');

                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "loan_{$loan->id}_payment_receipt",
                    'file_path' => $path,
                ]);
            }

            // Check if all installments are paid → mark loan as completed
            $unpaidCount = $loan->installments()->where('status', '!=', 'paid')->count();
            if ($unpaidCount === 0) {
                $loan->update(['status' => 'completed']);
            }

            // Audit log
            $this->logAudit('payment', 'loans', $loan->id, null, [
                'installment_ids' => $validated['installment_ids'],
                'receipt_number'  => $validated['receipt_number'],
            ]);
        });

        return redirect()->route('loans.index')
            ->with('success', 'تم تسجيل سداد القسط بنجاح.');
    }

    /**
     * Approve a pending loan (by admin / board).
     */
    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->route('loans.index')
                ->with('error', 'لا يمكن اعتماد هذا القرض.');
        }

        $oldValues = $loan->toArray();

        $loan->update([
            'status'      => 'active',
            'approved_by' => auth()->id(),
        ]);

        $this->logAudit('approve', 'loans', $loan->id, $oldValues, $loan->fresh()->toArray());

        return redirect()->route('loans.index')
            ->with('success', 'تم اعتماد القرض بنجاح.');
    }

    /**
     * Search members via AJAX (for the create-loan modal).
     */
    public function searchMembers(Request $request)
    {
        $search = $request->get('q', '');

        $members = Member::with('membershipInfo')
            ->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('national_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('membershipInfo', function ($q2) use ($search) {
                      $q2->where('membership_number', 'LIKE', "%{$search}%");
                  });
            })
            ->limit(10)
            ->get();

        return response()->json($members->map(function ($m) {
            return [
                'id'                => $m->id,
                'full_name'         => $m->full_name,
                'national_id'       => $m->national_id,
                'membership_number' => $m->membershipInfo->membership_number ?? '-',
                'has_active_loan'   => $m->membershipInfo
                    ? $m->membershipInfo->loans()->whereIn('status', ['active', 'pending', 'approved'])->exists()
                    : false,
            ];
        }));
    }

    /**
     * Create an audit log entry.
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
