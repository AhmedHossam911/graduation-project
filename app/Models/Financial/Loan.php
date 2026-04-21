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
        'membership_id', 'total_amount', 'months', 'installment_amount', 'status', 'approved_by'
    ];

    protected $casts = [
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
}
