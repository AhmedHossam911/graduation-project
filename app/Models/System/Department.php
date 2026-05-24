<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership\Member;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'status'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
