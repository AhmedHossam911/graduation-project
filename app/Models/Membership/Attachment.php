<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores references to uploaded files and physical documents belonging to a member.
 * Provides a highly dynamic, human-readable mapping for complex, system-generated document types (e.g., loan receipts, claim approvals).
 */
class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'type', 'file_path'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Translates backend file type identifiers into localized, human-readable Arabic labels.
     * Essential for UI display and generating organized file lists for the member.
     */
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
                'salary_letter' => 'خطاب بالمرتب الأساسي',
                'national_id' => 'بطاقة الرقم القومي',
                'retirement_decision' => 'قرار الإحالة للمعاش',
                'deductions_statement' => 'بيان بالمبالغ المخصومة',
                'appointment_letter' => 'خطاب بتاريخ التعيين',
                'release_form' => 'إخلاء طرف',
                'transfer_decision' => 'قرار النقل',
                'service_end_decision' => 'قرار إنهاء الخدمة',
                'death_certificate' => 'شهادة الوفاة',
                'heirs_ids' => 'بطاقات الرقم القومي للورثة',
                'inheritance_notice' => 'إعلام الوراثة',
                'signed_receipt' => 'توقيع باستلام المستحقات',
            ];
            return 'مرفق مطالبة' . (isset($map[$docType]) ? ' - ' . $map[$docType] : '');
        }
        
        // Custom translations for static types
        $staticMap = [
            'personal_photo' => 'صورة شخصية',
            'national_id_front' => 'بطاقة الرقم القومي (أمامي)',
            'national_id_back' => 'بطاقة الرقم القومي (خلفي)',
            'salary_letter' => 'مفردات مرتب',
            'national_id_card' => 'بطاقة الرقم القومي',
            'basic_salary_letter' => 'خطاب الأجر الأساسي',
            'work_declaration' => 'إقرار القيام بالعمل',
            'over_21_request' => 'طلب تجاوز فوق سن ٢١ عام',
            'appointment_decision' => 'قرار التعيين',
            'manual_request' => 'طلب يدوي بالتسجيل من خلال المكتب',
            'signed_membership_form' => 'استمارة العضوية الموقعة',
            'suspension_document' => 'مستند إيقاف العضوية',
        ];

        return $staticMap[$type] ?? $type;
    }
}
