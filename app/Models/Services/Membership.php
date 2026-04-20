<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(\App\Models\Membership\Member::class);
    }
}
