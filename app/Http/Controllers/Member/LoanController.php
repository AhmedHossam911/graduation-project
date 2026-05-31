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
        ], [
            'total_amount.required' => 'يرجى تحديد قيمة القرض.',
            'total_amount.numeric' => 'قيمة القرض يجب أن تكون رقماً.',
            'months.required' => 'يرجى تحديد مدة السداد.',
            'months.integer' => 'مدة السداد يجب أن تكون رقماً صحيحاً.',
        ]);

        $user = Auth::user();
        $member = $user->member;

        if (!$member || !$member->membershipInfo) {
            return back()->withInput()
                ->with('error', 'لا يوجد عضوية مسجلة.');
        }

        $forbiddenStatuses = ['pending_registration', 'pension_eligible', 'withdrawn', 'dismissed', 'membership_expired', 'suspended'];
        if (in_array($member->membershipInfo->status, $forbiddenStatuses)) {
            return back()->withInput()
                ->with('error', 'وفقاً لحالة العضوية الحالية، لا يمكن إنشاء القرض.');
        }

        $hasActiveLoan = $member->membershipInfo->loans()
            ->whereIn('status', ['active', 'pending', 'approved'])
            ->exists();

        if ($hasActiveLoan) {
            return back()->withInput()
                ->with('error', 'يوجد قرض نشط أو قيد الانتظار بالفعل.');
        }

        $totalPaidSubscriptions = $member->membershipInfo->subscriptions->where('status', 'paid')->sum('amount');
        if ($totalPaidSubscriptions < $validated['total_amount']) {
            return back()->withInput()
                ->with('error', "إجمالي الاشتراكات المدفوعة ({$totalPaidSubscriptions} ج.م) لا يغطي قيمة القرض المطلوبة ({$validated['total_amount']} ج.م).");
        }

        if ($member->employmentInfo && $member->employmentInfo->retirement_date) {
            $retirementDate = \Carbon\Carbon::parse($member->employmentInfo->retirement_date);
            $monthsRemaining = now()->startOfDay()->diffInMonths($retirementDate, false);
            if ($monthsRemaining < $validated['months']) {
                return back()->withInput()
                    ->with('error', 'المدة المتبقية لخدمتك أقل من فترة القرض المطلوبة.');
            }
        }

        $minYearsSubscribed = floatval(\App\Models\System\SystemSetting::get('loan_min_years_subscribed', 0));
        if ($minYearsSubscribed > 0) {
            $firstPaidSubscription = $member->membershipInfo->subscriptions->where('status', 'paid')->sortBy('created_at')->first();
            if (!$firstPaidSubscription) {
                return back()->withInput()
                    ->with('error', 'لم يتم سداد أي اشتراكات حتى الآن، لا يمكن طلب قرض.');
            }
            
            $yearsSubscribed = $firstPaidSubscription->created_at->diffInYears(now());
            if ($yearsSubscribed < $minYearsSubscribed) {
                return back()->withInput()
                    ->with('error', "لم يمر {$minYearsSubscribed} سنوات على أول اشتراك مدفوع (تاريخ أول اشتراك: {$firstPaidSubscription->created_at->format('Y-m-d')}).");
            }
        }

        $maxAmount = \App\Models\System\SystemSetting::get('loan_max_amount', 20000);
        $maxMonths = \App\Models\System\SystemSetting::get('loan_repayment_months', 36);

        if ($validated['total_amount'] > $maxAmount) {
            return back()->withInput()
                ->with('error', 'قيمة القرض تتجاوز الحد الأقصى المسموح به.');
        }

        if ($validated['months'] > $maxMonths) {
            return back()->withInput()
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
