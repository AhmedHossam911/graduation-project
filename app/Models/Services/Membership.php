<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership\Member;
use App\Models\Auth\User;
use App\Models\Financial\Loan;

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

    public function getRemainingLoanBalanceAttribute()
    {
        return $this->loans->where('status', 'active')->sum(function ($loan) {
            return $loan->remaining_loan_balance;
        });
    }

    public function getActiveLoansCountAttribute()
    {
        return $this->loans->where('status', 'active')->count();
    }

    public function getFeesPaidAttribute()
    {
        // Default to 100 if the user has any subscriptions, or keep 0
        return $this->subscriptions()->exists() ? 100 : 0;
    }

    public function getSubscriptionTotalAttribute()
    {
        return $this->subscriptions()->where('status', 'paid')->sum('amount') ?? 0;
    }
}
