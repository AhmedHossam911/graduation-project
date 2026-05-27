<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'json',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getArabicNameAttribute()
    {
        return self::translateName($this->name);
    }

    public static function translateName($name)
    {
        if (!$name) return 'غير محدد';
        $names = [
            'admin' => 'مدير النظام',
            'Admin' => 'مدير النظام',
            'employee' => 'موظف',
            'Employee' => 'موظف',
            'member' => 'عضو',
            'Member' => 'عضو',
        ];

        return $names[$name] ?? $name;
    }
}
