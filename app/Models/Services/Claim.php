<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_id', 'type', 'amount', 'status', 'attachment_receipt'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
