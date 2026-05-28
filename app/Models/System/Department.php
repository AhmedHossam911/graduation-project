<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership\Member;

/**
 * Represents a specific organizational unit or faculty (e.g., Faculty of Engineering, Medicine).
 * Members are assigned to departments to facilitate categorized reporting and grouping.
 */
class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'status'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
