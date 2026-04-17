<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
