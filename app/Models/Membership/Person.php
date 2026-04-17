<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->second_name} {$this->third_name} {$this->fourth_name}");
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }
}
