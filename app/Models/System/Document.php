<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];

    public function documentable()
    {
        return $this->morphTo();
    }
}
