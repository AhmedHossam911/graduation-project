<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'impersonator_id', 'action', 'table_name', 'record_id',
        'old_values', 'new_values', 'ip_address'
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    protected $appends = ['action_description'];

    public function getActionDescriptionAttribute()
    {
        // If the action already contains Arabic text, it's a custom pre-translated action
        if (preg_match('/\p{Arabic}/u', $this->action)) {
            return $this->action;
        }

        $table = strtolower($this->table_name);
        $action = strtolower($this->action);

        $tablesAr = [
            'members' => 'عضو',
            'memberships' => 'عضوية',
            'claims' => 'مطالبة',
            'loans' => 'قرض',
            'attachments' => 'مرفق',
            'subscriptions' => 'اشتراك',
            'installments' => 'قسط',
            'system_settings' => 'إعدادات النظام',
            'users' => 'مستخدم',
            'roles' => 'صلاحية',
            'permissions' => 'إذن',
            'departments' => 'قسم',
            'payments' => 'دفعة',
            'transactions' => 'حركة مالية',
            'audit_logs' => 'سجل عمليات',
        ];

        $actionsAr = [
            'create' => 'إنشاء',
            'created' => 'إنشاء',
            'update' => 'تحديث',
            'updated' => 'تحديث',
            'delete' => 'حذف',
            'deleted' => 'حذف',
            'suspend' => 'إيقاف',
            'suspended' => 'إيقاف',
            'approve' => 'اعتماد',
            'approved' => 'اعتماد',
            'finalize' => 'إنهاء',
            'finalized' => 'إنهاء',
            'upload_signed_form' => 'رفع إقرار موقع لـ',
            'upload_document' => 'رفع مستند لـ',
            'login' => 'تسجيل دخول',
            'logout' => 'تسجيل خروج',
            'pay' => 'سداد',
            'payment' => 'سداد',
        ];

        $tableName = $tablesAr[$table] ?? $this->table_name;
        $actionName = $actionsAr[$action] ?? $this->action;

        if (in_array($action, ['create', 'created', 'update', 'updated', 'delete', 'deleted', 'approve', 'approved', 'finalize', 'finalized', 'pay', 'payment'])) {
            return "تم {$actionName} {$tableName} بنجاح";
        }

        if (in_array($action, ['upload_signed_form', 'upload_document'])) {
            return "{$actionName} {$tableName} بنجاح";
        }

        if (in_array($action, ['suspend', 'suspended'])) {
            return "تم إيقاف {$tableName} بنجاح";
        }

        return "{$actionName} {$tableName}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function impersonator()
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }
}
