<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'code',
        'expires_at',
        'is_used',
        'used_at',
    ];
}
