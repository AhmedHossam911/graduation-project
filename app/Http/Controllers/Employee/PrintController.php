<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Financial\Transaction;
use App\Models\Services\Claim;
use App\Models\Financial\Loan;
use App\Models\Membership\Member;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function newMembershipReceipt($id)
    {
        $member = Member::with('membershipInfo')->findOrFail($id);
        return view('print.new_membership_receipt', compact('member'));
    }

    public function transaction($id)
    {
        $transaction = Transaction::with('membership.member')->findOrFail($id);
        return view('print.transaction', compact('transaction'));
    }

    public function claimDetails($id)
    {
        $claim = Claim::with(['membership.member.employmentInfo', 'membership.member.department', 'membership.subscriptions', 'membership.loans.installments'])->findOrFail($id);
        $claimTypes = Claim::CLAIM_TYPES;
        
        $basic_percentage = (float) \App\Models\System\SystemSetting::get('claim_basic_percentage', 145) / 100;
        $transfer_percentage = (float) \App\Models\System\SystemSetting::get('claim_transfer_resignation_percentage', 80) / 100;
        $funeral_expenses = (float) \App\Models\System\SystemSetting::get('claim_funeral_expenses', 0);
        
        $membership = $claim->membership;
        $joinFeeSub = $membership->subscriptions()->where('name', 'رسم الاشتراك بالصندوق')->first() 
            ?? $membership->subscriptions()->orderBy('id')->first();
        $joinFee = ($joinFeeSub && $joinFeeSub->status === 'paid') ? $joinFeeSub->amount : 0;
        
        $paidSubsQuery = $membership->subscriptions()->where('status', 'paid');
        if ($joinFeeSub) {
            $paidSubsQuery->where('id', '!=', $joinFeeSub->id);
        }
        $paidSubscriptionsAmount = $paidSubsQuery->sum('amount');
        $paidSubscriptionsCount = $paidSubsQuery->count();
        
        $overdueSubsQuery = $membership->subscriptions()->whereIn('status', ['unpaid', 'overdue'])->where('due_date', '<=', now());
        $overdueSubscriptionsAmount = $overdueSubsQuery->sum('amount');
        $overdueSubscriptionsCount = $overdueSubsQuery->count();
        
        $totalPaid = $joinFee + $paidSubscriptionsAmount;
        
        if (in_array($claim->type, ['transfer', 'resignation'])) {
            $insurance_benefit = $totalPaid * $transfer_percentage;
        } else {
            $insurance_benefit = $totalPaid * $basic_percentage;
            if ($claim->type === 'death') {
                $insurance_benefit += $funeral_expenses;
            }
        }
        
        $remaining_loan = $membership->remaining_loan_balance;
        $net_amount = $insurance_benefit - ($remaining_loan + $overdueSubscriptionsAmount);
        
        $unpaidMonths = $overdueSubscriptionsCount * 3;
        $paidMonths = $paidSubscriptionsCount * 3;
        
        $employmentJoinDate = \Carbon\Carbon::parse($membership->member->employmentInfo->join_date);
        $serviceDuration = $employmentJoinDate->diff(now());
        $serviceYears = $serviceDuration->y;
        $serviceMonths = $serviceDuration->m;

        return view('print.claim_details', compact(
            'claim', 'claimTypes', 'joinFee', 'paidSubscriptionsAmount', 
            'insurance_benefit', 'net_amount', 'unpaidMonths', 'paidMonths', 
            'serviceYears', 'serviceMonths', 'overdueSubscriptionsAmount'
        ));
    }

    public function claimReceipt($id)
    {
        $claim = Claim::with('membership.member')->findOrFail($id);
        return view('print.claim_receipt', compact('claim'));
    }

    public function claimDeclaration($id)
    {
        $claim = Claim::with('membership.member')->findOrFail($id);
        $member = $claim->membership->member;
        return view('print.claim_declaration', compact('claim', 'member'));
    }

    public function loanDeclaration($id)
    {
        $loan = Loan::with('membership.member')->findOrFail($id);
        $member = $loan->membership->member;
        $membership = $loan->membership;
        return view('print.loan_declaration', compact('loan', 'member', 'membership'));
    }

    public function newLoanDeclaration(Request $request, $memberId)
    {
        $member = Member::with('membershipInfo')->findOrFail($memberId);
        $membership = $member->membershipInfo;
        
        $loan = new Loan([
            'base_amount' => $request->query('amount'),
            'months' => $request->query('months'),
            'interest_amount' => $request->query('interest'),
            'installment_amount' => $request->query('installment'),
            'total_amount' => $request->query('total')
        ]);
        
        return view('print.loan_declaration', compact('loan', 'member', 'membership'));
    }

    public function boardDetails($id)
    {
        $activeLoan = Loan::with('membership.member', 'installments')->findOrFail($id);
        $member = $activeLoan->membership->member;
        $membership = $activeLoan->membership;
        return view('print.board_details', compact('activeLoan', 'member', 'membership'));
    }
}
