<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a single monthly installment payment toward an active loan.
 * Tracks the due date, amount, payment status, and whether it was paid as part of an early repayment process.
 */
class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id', 'amount', 'due_date', 'status', 'is_prepayment'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_prepayment' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function transaction()
    {
        return $this->morphOne(\App\Models\Financial\Transaction::class, 'reference');
    }
}
