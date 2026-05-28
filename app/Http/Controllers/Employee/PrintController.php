<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Financial\Transaction;
use App\Models\Services\Claim;
use App\Models\Financial\Loan;
use App\Models\Membership\Member;
use Illuminate\Http\Request;
use App\Services\ClaimCalculationService;

class PrintController extends Controller
{
    protected $claimCalculationService;

    public function __construct(ClaimCalculationService $claimCalculationService)
    {
        $this->claimCalculationService = $claimCalculationService;
    }

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
        
        $calculations = $this->claimCalculationService->calculate($claim);

        return view('print.claim_details', array_merge(
            compact('claim', 'claimTypes'),
            $calculations
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
