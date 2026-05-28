<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership\Member;
use App\Models\Auth\User;
use App\Models\Financial\Loan;

/**
 * Represents the official, active membership status of a participant in the fund.
 * Links the Member profile to their financial activities including Subscriptions (dues), Loans, and Claims.
 * Crucial for determining active participation and eligibility for loans.
 */
class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'membership_number', 'status', 'declaration_accepted', 'approved_by'
    ];

    protected $casts = [
        'declaration_accepted' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Aggregates the remaining unpaid balance across all currently active loans associated with this membership.
     */
    public function getRemainingLoanBalanceAttribute()
    {
        return $this->loans->where('status', 'active')->sum(function ($loan) {
            return $loan->remaining_loan_balance;
        });
    }

    /**
     * Counts how many active loans the member currently has (business rules typically restrict this to 1).
     */
    public function getActiveLoansCountAttribute()
    {
        return $this->loans->where('status', 'active')->count();
    }

    /**
     * Retrieves the initial admission fee paid when the membership was first created.
     */
    public function getFeesPaidAttribute()
    {
        // Default to 100 if the user has any subscriptions, or keep 0
        return $this->subscriptions()->exists() ? 100 : 0;
    }

    /**
     * Calculates the absolute total of all monthly subscriptions (dues) paid by the member to date.
     */
    public function getSubscriptionTotalAttribute()
    {
        return $this->subscriptions()->where('status', 'paid')->sum('amount') ?? 0;
    }
}
