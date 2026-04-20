<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = [];

    /**
     * Get the user who performed this action.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }

    /**
     * Get a human-readable label for the action.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'إنشاء',
            'update' => 'تعديل',
            'delete' => 'حذف',
            'login'  => 'تسجيل دخول',
            'logout' => 'تسجيل خروج',
            default  => $this->action,
        };
    }

    /**
     * Get a human-readable label for the entity type.
     */
    public function getEntityLabelAttribute(): string
    {
        return match ($this->entity_type) {
            'member'       => 'عضو',
            'subscription' => 'اشتراك',
            'loan'         => 'قرض',
            'installment'  => 'قسط',
            'claim'        => 'مطالبة',
            'payment'      => 'دفعة',
            'user'         => 'مستخدم',
            default        => $this->entity_type ?? '—',
        };
    }
}
