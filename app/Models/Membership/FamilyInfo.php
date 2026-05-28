<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores basic family information for a member (spouse and children).
 * Used primarily for documentation purposes in the event of an insurance claim.
 */
class FamilyInfo extends Model
{
    use HasFactory;

    protected $table = 'family_info';

    protected $fillable = [
        'member_id', 'children_count', 'spouse_name', 'spouse_phone',
        'child_name', 'spouse_workplace', 'child_workplace'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
