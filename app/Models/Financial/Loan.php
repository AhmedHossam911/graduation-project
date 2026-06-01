<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Membership;
use App\Models\Auth\User;

/**
 * Represents a financial loan issued to a member.
 * Tracks the total principal, applied interest, repayment duration (months), and the monthly installment amount.
 * A loan is linked to multiple Installment records.
 */
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_id', 'base_amount', 'interest_amount', 'total_amount', 'months', 'installment_amount', 'status', 'approved_by'
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    
    /**
     * Dynamically calculates the outstanding loan balance.
     * Deducts the sum of all 'paid' installments from the total expected loan amount.
     */
    public function getRemainingLoanBalanceAttribute()
    {
        return $this->total_amount - $this->installments()->where('status', 'paid')->sum('amount');
    }

    public function transaction()
    {
        return $this->morphOne(\App\Models\Financial\Transaction::class, 'reference');
    }
}
