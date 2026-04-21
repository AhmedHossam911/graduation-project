<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
