<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a monthly financial subscription (due) that a member must pay to remain active.
 * Tracks payment status and maintains timestamps for successive arrears warnings to enforce fund bylaws.
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_id', 'name', 'amount', 'due_date', 'status',
        'last_warning_sent_at', 'first_warning_sent_at',
        'second_warning_sent_at', 'notice_sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'last_warning_sent_at' => 'datetime',
        'first_warning_sent_at' => 'datetime',
        'second_warning_sent_at' => 'datetime',
        'notice_sent_at' => 'datetime',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function transaction()
    {
        return $this->morphOne(\App\Models\Financial\Transaction::class, 'reference');
    }
}
