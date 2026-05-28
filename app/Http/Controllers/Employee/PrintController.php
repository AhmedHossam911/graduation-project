<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Financial\Transaction;
use App\Models\Services\Claim;
use App\Models\Financial\Loan;
use App\Models\Membership\Member;
use Illuminate\Http\Request;
use App\Services\ClaimCalculationService;

/**
 * Manages the generation of printable documents, receipts, and official declarations.
 * Ensures that members and employees can output physical copies of financial and membership records.
 */
class PrintController extends Controller
{
    protected $claimCalculationService;

    public function __construct(ClaimCalculationService $claimCalculationService)
    {
        $this->claimCalculationService = $claimCalculationService;
    }

    /**
     * Generate an official receipt for a newly created membership application.
     */
    public function newMembershipReceipt($id)
    {
        // Fetch the member along with their core membership data to populate the receipt.
        $member = Member::with('membershipInfo')->findOrFail($id);

        return view('print.new_membership_receipt', compact('member'));
    }

    /**
     * Generate a physical printout of a specific financial transaction.
     */
    public function transaction($id)
    {
        // Eager load the member data associated with the transaction for receipt details.
        $transaction = Transaction::with('membership.member')->findOrFail($id);

        return view('print.transaction', compact('transaction'));
    }

    /**
     * Generate a comprehensive statement for an insurance claim.
     * Computes real-time financials (deducting loans/arrears) via the ClaimCalculationService.
     */
    public function claimDetails($id)
    {
        // Eager load all necessary relationships (Employment, Department, Subscriptions, Loans)
        // to provide a full context for the claim calculation.
        $claim = Claim::with(['membership.member.employmentInfo', 'membership.member.department', 'membership.subscriptions', 'membership.loans.installments'])->findOrFail($id);
        
        // Retrieve the localized claim types for display purposes.
        $claimTypes = Claim::CLAIM_TYPES;

        // Delegate complex financial calculations (deductions, arrears, net amount) to the dedicated service.
        $calculations = $this->claimCalculationService->calculate($claim);

        // Merge the claim model and calculation results into the view.
        return view('print.claim_details', array_merge(
            compact('claim', 'claimTypes'),
            $calculations
        ));
    }

    /**
     * Generate a simple payment receipt once an insurance claim has been disbursed.
     */
    public function claimReceipt($id)
    {
        $claim = Claim::with('membership.member')->findOrFail($id);
        return view('print.claim_receipt', compact('claim'));
    }

    /**
     * Generate an official declaration form required for processing a claim.
     */
    public function claimDeclaration($id)
    {
        $claim = Claim::with('membership.member')->findOrFail($id);
        $member = $claim->membership->member;
        return view('print.claim_declaration', compact('claim', 'member'));
    }

    /**
     * Generate an official loan declaration form for an already existing loan record.
     */
    public function loanDeclaration($id)
    {
        $loan = Loan::with('membership.member')->findOrFail($id);
        $member = $loan->membership->member;
        $membership = $loan->membership;
        return view('print.loan_declaration', compact('loan', 'member', 'membership'));
    }

    /**
     * Generate an official loan declaration form for a proposed loan BEFORE it is officially stored.
     * Used dynamically during the loan application process to capture physical signatures.
     */
    public function newLoanDeclaration(Request $request, $memberId)
    {
        // Fetch the member's profile and membership status.
        $member = Member::with('membershipInfo')->findOrFail($memberId);
        $membership = $member->membershipInfo;

        // Instantiate a new Loan model strictly in memory (without saving to the database).
        // This is necessary to populate the print view with the proposed loan figures.
        $loan = new Loan([
            'base_amount' => $request->query('amount'),
            'months' => $request->query('months'),
            'interest_amount' => $request->query('interest'),
            'installment_amount' => $request->query('installment'),
            'total_amount' => $request->query('total')
        ]);

        return view('print.loan_declaration', compact('loan', 'member', 'membership'));
    }

    /**
     * Generate a detailed report of a member's loan for the Board of Directors' approval.
     */
    public function boardDetails($id)
    {
        $activeLoan = Loan::with('membership.member', 'installments')->findOrFail($id);
        $member = $activeLoan->membership->member;
        $membership = $activeLoan->membership;
        return view('print.board_details', compact('activeLoan', 'member', 'membership'));
    }
}
