<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Manages One-Time Passwords (OTPs) generated for Two-Factor Authentication (2FA) and password resets.
 * Ensures security by tracking expiration times and whether a code has already been consumed.
 */
class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'code', 'expires_at', 'is_used'];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
