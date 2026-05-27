<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'type', 'file_path'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getReadableTypeAttribute()
    {
        $type = $this->type;
        
        if (str_starts_with($type, 'subscription_') && str_ends_with($type, '_receipt')) {
            return 'إيصال سداد اشتراك';
        }
        if (str_starts_with($type, 'loan_') && str_ends_with($type, '_payment_receipt')) {
            return 'إيصال سداد قسط قرض';
        }
        if (str_starts_with($type, 'installment_') && str_ends_with($type, '_receipt')) {
            return 'إيصال سداد قسط قرض';
        }
        if (str_starts_with($type, 'loan_') && str_ends_with($type, '_early_repayment_receipt')) {
            return 'إيصال سداد مبكر لقرض';
        }
        if (str_starts_with($type, 'loan_') && str_ends_with($type, '_check')) {
            return 'صورة شيك القرض';
        }
        if (str_starts_with($type, 'loan_') && str_ends_with($type, '_board_approval')) {
            return 'موافقة المجلس على القرض';
        }
        if ($type === 'loan_declaration') {
            return 'إقرار القرض';
        }
        if (str_starts_with($type, 'claim_') && str_ends_with($type, '_approval_receipt')) {
            return 'إيصال صرف المطالبة';
        }
        if (str_starts_with($type, 'claim_') && str_ends_with($type, '_signed_receipt')) {
            return 'الإقرار الموقع لاستلام المطالبة';
        }
        if (preg_match('/^claim_\d+_(.+)$/', $type, $matches)) {
            $docType = $matches[1];
            $map = [
                'minors_birth_certificates' => 'شهادات ميلاد القصر',
                'guardianship_decision' => 'قرار الوصاية',
            ];
            return 'مرفق مطالبة' . (isset($map[$docType]) ? ' - ' . $map[$docType] : '');
        }
        
        // Custom translations for static types
        $staticMap = [
            'personal_photo' => 'صورة شخصية',
            'national_id_front' => 'بطاقة الرقم القومي (أمامي)',
            'national_id_back' => 'بطاقة الرقم القومي (خلفي)',
            'salary_letter' => 'مفردات مرتب',
        ];

        return $staticMap[$type] ?? $type;
    }
}
