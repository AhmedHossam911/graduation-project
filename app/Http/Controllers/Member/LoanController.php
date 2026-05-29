<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->member?->membershipInfo;
        
        $activeLoan = $membership?->loans()->where('status', 'active')->first();
        
        if ($membership && $membership->status === 'active') {
            return view('member.active.loans.index', compact('user', 'membership', 'activeLoan'));
        }
        
        return view('member.guest.loans.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_amount'     => ['required', 'numeric'],
            'months'           => ['required', 'integer'],
        ]);

        $user = Auth::user();
        $member = $user->member;

        if (!$member || !$member->membershipInfo) {
            return redirect()->route('member.loans.index')
                ->with('error', 'لا يوجد عضوية مسجلة.');
        }

        $forbiddenStatuses = ['pending_registration', 'pension_eligible', 'withdrawn', 'dismissed', 'membership_expired', 'suspended'];
        if (in_array($member->membershipInfo->status, $forbiddenStatuses)) {
            return redirect()->route('member.loans.index')
                ->with('error', 'وفقاً لحالة العضوية الحالية، لا يمكن إنشاء القرض.');
        }

        $hasActiveLoan = $member->membershipInfo->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('member.loans.index')
                ->with('error', 'يوجد قرض نشط أو قيد الانتظار بالفعل.');
        }

        $totalPaidSubscriptions = $member->membershipInfo->subscriptions->where('status', 'paid')->sum('amount');
        if ($totalPaidSubscriptions < $validated['total_amount']) {
            return redirect()->route('member.loans.index')
                ->with('error', "إجمالي الاشتراكات المدفوعة ({$totalPaidSubscriptions} ج.م) لا يغطي قيمة القرض المطلوبة ({$validated['total_amount']} ج.م).");
        }

        if ($member->employmentInfo && $member->employmentInfo->retirement_date) {
            $retirementDate = \Carbon\Carbon::parse($member->employmentInfo->retirement_date);
            $monthsRemaining = now()->startOfDay()->diffInMonths($retirementDate, false);
            if ($monthsRemaining < $validated['months']) {
                return redirect()->route('member.loans.index')
                    ->with('error', 'المدة المتبقية لخدمتك أقل من فترة القرض المطلوبة.');
            }
        }

        $maxAmount = \App\Models\System\SystemSetting::get('loan_max_amount', 20000);
        $maxMonths = \App\Models\System\SystemSetting::get('loan_repayment_months', 36);

        if ($validated['total_amount'] > $maxAmount) {
            return redirect()->route('member.loans.index')
                ->with('error', 'قيمة القرض تتجاوز الحد الأقصى المسموح به.');
        }

        if ($validated['months'] > $maxMonths) {
            return redirect()->route('member.loans.index')
                ->with('error', 'مدة القرض تتجاوز الحد الأقصى المسموح به.');
        }

        $baseAmount = $validated['total_amount'];
        $interestRate = floatval(\App\Models\System\SystemSetting::get('loan_interest_rate', 8));
        $years = $validated['months'] / 12;
        $interestAmount = round($interestRate / 100 * $baseAmount * $years, 2);
        $totalWithInterest = $baseAmount + $interestAmount;
        $installmentAmount = round($totalWithInterest / $validated['months'], 2);

        $loan = \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, $member, $baseAmount, $interestAmount, $totalWithInterest, $installmentAmount, $user) {
            $loan = \App\Models\Financial\Loan::create([
                'membership_id'      => $member->membershipInfo->id,
                'base_amount'        => $baseAmount,
                'interest_amount'    => $interestAmount,
                'total_amount'       => $totalWithInterest,
                'months'             => $validated['months'],
                'installment_amount' => $installmentAmount,
                'status'             => 'pending',
                'digital_declaration' => true,
            ]);


            \App\Models\System\AuditLog::create([
                'user_id'    => $user->id,
                'action'     => 'create',
                'table_name' => 'loans',
                'record_id'  => $loan->id,
                'old_values' => null,
                'new_values' => $loan->toArray(),
                'ip_address' => request()->ip(),
            ]);

            return $loan;
        });

        return redirect()->route('member.loans.index')
            ->with('success', 'تم تقديم طلب القرض بنجاح.');
    }
}
