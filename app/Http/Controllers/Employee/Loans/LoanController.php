<?php

namespace App\Http\Controllers\Employee\Loans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Financial\Transaction;
use App\Models\Membership\Member;
use App\Models\Membership\Attachment;
use App\Models\System\AuditLog;
use App\Models\System\SystemSetting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
    /**
     * Display a listing of all loans with search, filters, and statistics.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

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

        return view('employee.loans.index', compact(
            'loans',
            'activeLoansThisMonth',
            'pendingLoansCount',
            'overdueInstallmentsCount'
        ));
    }

    private function buildFilteredQuery(Request $request)
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

        // Filter by status or department (combined in the 'department' parameter from view)
        if ($request->filled('department') && $request->department !== 'all') {
            $filterValue = $request->department;

            if (is_numeric($filterValue)) {
                // Filter by department ID
                $query->whereHas('membership.member', function ($q) use ($filterValue) {
                    $q->where('department_id', $filterValue);
                });
            } elseif ($filterValue === 'overdue') {
                // Loans that have overdue installments
                $query->whereHas('installments', function ($q) {
                    $q->where('status', 'overdue');
                });
            } else {
                // Filter by status (pending, active, etc.)
                $query->where('status', $filterValue);
            }
        }

        // Keep legacy status filter support just in case
        if ($request->filled('status') && $request->status !== 'all' && !$request->filled('department')) {
            $status = $request->status;
            if ($status === 'overdue') {
                $query->whereHas('installments', function ($q) {
                    $q->where('status', 'overdue');
                });
            } else {
                $query->where('status', $status);
            }
        }

        return $query;
    }

    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        return Excel::download(new LoansExport($query), 'loans.xlsx');
    }

    /**
     * Validate loan request via AJAX before redirecting
     */
    public function validateLoanRequest(Request $request)
    {
        $validated = $request->validate([
            'member_id'    => ['required', 'exists:members,id'],
            'total_amount' => ['required', 'numeric'],
            'months'       => ['required', 'integer', 'min:1'],
        ]);

        $member = Member::with('membershipInfo.subscriptions', 'employmentInfo')->findOrFail($validated['member_id']);

        if (!$member->membershipInfo) {
            return response()->json(['success' => false, 'message' => 'لا يوجد عضوية مسجلة لهذا العضو.']);
        }

        // Check Membership Status
        $forbiddenStatuses = ['pending_registration', 'pension_eligible', 'withdrawn', 'dismissed', 'membership_expired', 'suspended'];
        if (in_array($member->membershipInfo->status, $forbiddenStatuses)) {
            return response()->json(['success' => false, 'message' => 'وفقاً لحالة العضوية الحالية، لا يمكن إنشاء القرض.']);
        }

        $hasActiveLoan = $member->membershipInfo->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->exists();

        if ($hasActiveLoan) {
            return response()->json(['success' => false, 'message' => 'يوجد قرض نشط أو قيد الانتظار بالفعل لهذا العضو.']);
        }

        $totalPaidSubscriptions = $member->membershipInfo->subscriptions()->where('status', 'paid')->sum('amount');
        if ($totalPaidSubscriptions < $validated['total_amount']) {
            return response()->json(['success' => false, 'message' => 'إجمالي الاشتراكات المدفوعة لا يغطي قيمة القرض المطلوبة.']);
        }

        if ($member->employmentInfo && $member->employmentInfo->retirement_date) {
            $retirementDate = Carbon::parse($member->employmentInfo->retirement_date);
            $monthsRemaining = now()->startOfDay()->diffInMonths($retirementDate, false);
            if ($monthsRemaining < $validated['months']) {
                return response()->json(['success' => false, 'message' => 'المدة المتبقية لخدمة العضو أقل من فترة القرض المطلوبة.']);
            }
        }

        $maxAmount = SystemSetting::get('loan_max_amount', 20000);
        $maxMonths = SystemSetting::get('loan_repayment_months', 36);

        if ($validated['total_amount'] > $maxAmount) {
            return response()->json(['success' => false, 'message' => 'قيمة القرض تتجاوز الحد الأقصى المسموح به.']);
        }

        if ($validated['months'] > $maxMonths) {
            return response()->json(['success' => false, 'message' => 'مدة القرض تتجاوز الحد الأقصى المسموح به.']);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created loan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'        => ['required', 'exists:members,id'],
            'total_amount'     => ['required', 'numeric'],
            'months'           => ['required', 'integer'],
            'declaration_file' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
        ]);

        $member = Member::with('membershipInfo.loans')->findOrFail($validated['member_id']);

        if (!$member->membershipInfo) {
            return redirect()->route('loans.index')
                ->with('error', 'لا يوجد عضوية مسجلة لهذا العضو.');
        }

        $forbiddenStatuses = ['pending_registration', 'pension_eligible', 'withdrawn', 'dismissed', 'membership_expired', 'suspended'];
        if (in_array($member->membershipInfo->status, $forbiddenStatuses)) {
            return redirect()->route('members.show', ['member' => $member->id, 'tab' => 'قروض'])
                ->with('error', 'وفقاً لحالة العضوية الحالية، لا يمكن إنشاء القرض.');
        }

        // Business rule: only 1 active loan per member
        $hasActiveLoan = $member->membershipInfo->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('members.show', ['member' => $member->id, 'tab' => 'قروض'])
                ->with('error', 'يوجد قرض نشط بالفعل لهذا العضو. لا يمكن إنشاء قرض جديد.');
        }

        // Business rule: Paid subscriptions must be >= requested loan amount
        $totalPaidSubscriptions = $member->membershipInfo->subscriptions()->where('status', 'paid')->sum('amount');
        if ($totalPaidSubscriptions < $validated['total_amount']) {
             return redirect()->route('members.show', ['member' => $member->id, 'tab' => 'قروض'])
                ->with('error', 'إجمالي الاشتراكات المدفوعة لا يغطي قيمة القرض المطلوبة.');
        }

        if ($member->employmentInfo && $member->employmentInfo->retirement_date) {
            $retirementDate = Carbon::parse($member->employmentInfo->retirement_date);
            $monthsRemaining = now()->startOfDay()->diffInMonths($retirementDate, false);
            if ($monthsRemaining < $validated['months']) {
                return redirect()->route('members.show', ['member' => $member->id, 'tab' => 'قروض'])
                    ->with('error', 'المدة المتبقية لخدمة العضو أقل من فترة القرض المطلوبة.');
            }
        }

        $baseAmount = $validated['total_amount'];
        $interestRate = floatval(SystemSetting::get('loan_interest_rate', 8));
        $years = $validated['months'] / 12;
        $interestAmount = round($interestRate / 100 * $baseAmount * $years, 2);
        $totalWithInterest = $baseAmount + $interestAmount;
        $installmentAmount = round($totalWithInterest / $validated['months'], 2);

        $loan = DB::transaction(function () use ($validated, $request, $member, $baseAmount, $interestAmount, $totalWithInterest, $installmentAmount) {
            $loan = Loan::create([
                'membership_id'      => $member->membershipInfo->id,
                'base_amount'        => $baseAmount,
                'interest_amount'    => $interestAmount,
                'total_amount'       => $totalWithInterest,
                'months'             => $validated['months'],
                'installment_amount' => $installmentAmount,
                'status'             => 'pending',
            ]);

            if ($request->hasFile('declaration_file')) {
                $path = $request->file('declaration_file')->store('members/declarations', 'public');
                Attachment::create([
                    'member_id' => $member->id,
                    'file_path' => $path,
                    'type'      => 'loan_declaration',
                ]);
            }

            // Audit log
            $this->logAudit('create', 'loans', $loan->id, null, $loan->toArray());

            return $loan;
        });

        return redirect()->route('members.show', ['member' => $member->id, 'tab' => 'قروض'])
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

        return view('employee.loans.show', compact('loan'));
    }

    /**
     * Display all previous loans for a member.
     */
    public function previousLoans(Member $member)
    {
        $member->load('membershipInfo.loans.installments');
        $loans = collect();
        if ($member->membershipInfo) {
            $loans = $member->membershipInfo->loans()->with('installments')->latest()->get();
        }

        return view('employee.loans.show', compact('member', 'loans'));
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
     * Get loan data via AJAX (for the record payment modal).
     */
    public function getLoanData(Loan $loan)
    {
        \Log::info('Fetching loan data for ID: ' . $loan->id);
        $loan->load('membership.member');

        $unpaidInstallments = $loan->installments()
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('due_date')
            ->get();

        $data = [
            'id' => $loan->id,
            'member_name' => $loan->membership->member->full_name ?? 'غير متوفر',
            'membership_number' => $loan->membership->membership_number ?? '-',
            'national_id' => $loan->membership->member->national_id ?? '-',
            'unpaid_installments' => $unpaidInstallments->map(function ($inst) {
                // Return formatted date like 'أبريل 2026'
                $date = \Carbon\Carbon::parse($inst->due_date)->locale('ar');
                return [
                    'id' => $inst->id,
                    'amount' => $inst->amount,
                    'month_year' => $date->translatedFormat('F Y'),
                ];
            })
        ];

        \Log::info('Returning loan data: ' . json_encode($data));
        return response()->json($data);
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
                'id'                => $m->membershipInfo->id ?? null,
                'member_id'         => $m->id,
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

    /**
     * Start a loan (Modal 3)
     */
    public function startLoan(Request $request, Loan $loan)
    {
        $request->validate([
            'check_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'board_approval_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $loan->load('membership');
        $oldValues = $loan->toArray();

        DB::transaction(function () use ($request, $loan, $oldValues) {
            $loan->update([
                'status' => 'active'
            ]);

            // Generate installment schedule when loan is confirmed/started
            for ($i = 1; $i <= $loan->months; $i++) {
                Installment::create([
                    'loan_id'  => $loan->id,
                    'amount'   => $loan->installment_amount,
                    'due_date' => now()->addMonths($i)->startOfMonth(),
                    'status'   => 'unpaid',
                ]);
            }

            $memberId = $loan->membership->member_id;
            $checkPath = null;

            if ($request->hasFile('check_image')) {
                $checkPath = $request->file('check_image')->store("members/{$memberId}/loans/{$loan->id}", 'public');
                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "loan_{$loan->id}_check",
                    'file_path' => $checkPath,
                ]);
            }

            if ($request->hasFile('board_approval_image')) {
                $path = $request->file('board_approval_image')->store("members/{$memberId}/loans/{$loan->id}", 'public');
                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "loan_{$loan->id}_board_approval",
                    'file_path' => $path,
                ]);
            }

            // Record OUT transaction for loan start
            Transaction::create([
                'membership_id' => $loan->membership_id,
                'reference_type' => Loan::class,
                'reference_id' => $loan->id,
                'amount' => $loan->base_amount ?? $loan->total_amount, // The actual money given is base_amount
                'type' => 'OUT',
                'method' => 'bank_transfer',
                'category' => 'loan_start',
                'receipt_no' => null, // Or check number if you collect it
                'attachment_path' => $checkPath,
                'created_by' => auth()->id(),
            ]);

            $this->logAudit('start', 'loans', $loan->id, $oldValues, $loan->fresh()->toArray());
        });

        return back()->with('success', 'تم بدء القرض بنجاح.');
    }

    /**
     * Cancel a loan request (Modal 4)
     */
    public function cancelLoan(Request $request, Loan $loan)
    {
        $request->validate([
            'reason' => 'required|string',
            'details' => 'required|string',
        ]);

        $oldValues = $loan->toArray();

        DB::transaction(function () use ($request, $loan, $oldValues) {
            $loan->update([
                'status' => 'rejected'
            ]);

            $this->logAudit('cancel', 'loans', $loan->id, $oldValues, array_merge($loan->fresh()->toArray(), [
                'cancel_reason' => $request->reason,
                'cancel_details' => $request->details,
            ]));
            
            // Delete pending installments if they exist
            $loan->installments()->where('status', 'unpaid')->delete();
        });

        return back()->with('success', 'تم إلغاء طلب القرض بنجاح.');
    }

    /**
     * Pay a single installment (Modal 5)
     */
    public function payInstallment(Request $request, Installment $installment)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,salary_deduction,university_payment_order',
            'receipt_number' => 'required|string|max:255',
            'receipt_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $installment->load('loan.membership');
        $oldValues = $installment->toArray();

        DB::transaction(function () use ($request, $installment, $oldValues) {
            $installment->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);

            $loan = $installment->loan;
            $memberId = $loan->membership->member_id;

            $path = null;
            if ($request->hasFile('receipt_image')) {
                $path = $request->file('receipt_image')
                    ->store("members/{$memberId}/loans/{$loan->id}", 'public');

                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "installment_{$installment->id}_receipt",
                    'file_path' => $path,
                ]);
            }

            \App\Models\Financial\Transaction::create([
                'membership_id' => $loan->membership_id,
                'reference_type' => Installment::class,
                'reference_id' => $installment->id,
                'amount' => $installment->amount,
                'type' => 'IN',
                'method' => $request->payment_method,
                'category' => 'loan_installment',
                'receipt_no' => $request->receipt_number,
                'attachment_path' => $path,
                'created_by' => auth()->id(),
            ]);

            // Check if all installments are paid → mark loan as completed
            $unpaidCount = $loan->installments()->where('status', '!=', 'paid')->count();
            if ($unpaidCount === 0) {
                $loan->update(['status' => 'completed']);
            }

            // Audit log
            $this->logAudit('payment', 'installments', $installment->id, $oldValues, array_merge($installment->fresh()->toArray(), [
                'receipt_number' => $request->receipt_number,
            ]));
        });

        return back()->with('success', 'تم سداد القسط بنجاح.');
    }

    /**
     * Early repayment of entire loan (Modal 6)
     */
    public function earlyRepayment(Request $request, Loan $loan)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,salary_deduction,university_payment_order',
            'receipt_number' => 'required|string|max:255',
            'receipt_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $loan->load('membership', 'installments');
        
        // Business Rule: Early repayment is only allowed if remaining time is 6 months or less
        $unpaidCount = $loan->installments()->where('status', '!=', 'paid')->count();
        if ($unpaidCount > 6) {
            return back()->with('error', 'لا يمكن السداد المبكر إلا إذا كان المتبقي من القرض 6 أشهر أو أقل.');
        }

        // Calculate early repayment amount: subtract remaining interest
        // Amount to pay = remaining principal
        // Monthly principal = base_amount / months
        $monthlyPrincipal = ($loan->base_amount ?? $loan->total_amount) / $loan->months;
        $remainingPrincipalToPay = round($monthlyPrincipal * $unpaidCount, 2);

        $oldValues = $loan->toArray();

        DB::transaction(function () use ($request, $loan, $oldValues, $remainingPrincipalToPay) {
            $loan->installments()->where('status', '!=', 'paid')->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);

            $loan->update([
                'status' => 'completed',
            ]);

            $memberId = $loan->membership->member_id;

            $path = null;
            if ($request->hasFile('receipt_image')) {
                $path = $request->file('receipt_image')
                    ->store("members/{$memberId}/loans/{$loan->id}", 'public');

                Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "loan_{$loan->id}_early_repayment_receipt",
                    'file_path' => $path,
                ]);
            }

            Transaction::create([
                'membership_id' => $loan->membership_id,
                'reference_type' => Loan::class,
                'reference_id' => $loan->id,
                'amount' => $remainingPrincipalToPay,
                'type' => 'IN',
                'method' => $request->payment_method,
                'category' => 'early_repayment',
                'receipt_no' => $request->receipt_number,
                'attachment_path' => $path,
                'created_by' => auth()->id(),
            ]);

            // Audit log
            $this->logAudit('early_repayment', 'loans', $loan->id, $oldValues, array_merge($loan->fresh()->toArray(), [
                'receipt_number' => $request->receipt_number,
                'early_repayment_amount' => $remainingPrincipalToPay,
            ]));
        });

        return back()->with('success', 'تم تسجيل السداد المبكر بنجاح.');
    }
}
