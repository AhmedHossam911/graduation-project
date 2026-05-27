<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Membership;
use App\Models\Auth\User;

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
    
        public function getRemainingLoanBalanceAttribute()
        {
            return $this->total_amount - $this->installments()->sum('amount');
        }

    public function transaction()
    {
        return $this->morphOne(\App\Models\Financial\Transaction::class, 'reference');
    }
}
