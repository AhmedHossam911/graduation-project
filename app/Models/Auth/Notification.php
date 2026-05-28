<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents an in-app notification sent to a specific user.
 * Used for system alerts, warnings, and updates regarding memberships and financial status.
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'message', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
