<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Membership\Member;
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id', 'name', 'email', 'password', 'is_restricted', 'last_login', 'faculties', 'custom_permissions', 'email_verified_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_restricted' => 'boolean',
        'last_login' => 'datetime',
        'faculties' => 'array',
        'custom_permissions' => 'array',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function hasPermission($permission)
    {
        if ($this->role && strtolower($this->role->name) === 'admin') {
            return true;
        }

        $userPermissions = $this->custom_permissions ?? [];
        
        // Use strict mode in in_array to prevent loose comparison (e.g., 'string' == true)
        return in_array($permission, (array)$userPermissions, true);
    }

    public function isAdmin()
    {
        return $this->role && strtolower($this->role->name) === 'admin';
    }

    public function isEmployee()
    {
        return $this->role && strtolower($this->role->name) === 'employee';
    }

    public function isMember()
    {
        return $this->role && strtolower($this->role->name) === 'member';
    }
}
