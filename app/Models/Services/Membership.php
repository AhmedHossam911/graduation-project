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
}
