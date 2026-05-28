<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Membership;
use App\Models\Auth\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'membership_id',
        'reference_type',
        'reference_id',
        'amount',
        'type',
        'method',
        'category',
        'description',
        'receipt_no',
        'attachment_path',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /* ──────────────── Type constants ──────────────── */

    const TYPE_IN  = 'IN';
    const TYPE_OUT = 'OUT';

    /* ──────────────── Payment method labels (Arabic) ──────────────── */

    const METHOD_LABELS = [
        'cash'             => 'نقدي',
        'bank_transfer'    => 'تحويل بنكي',
        'check'            => 'شيك',
        'salary_deduction'         => 'خصم من المرتب',
        'pre_letter'               => 'دفع بجواب مسبق',
        'university_payment_order' => 'أمر دفع من الجامعة',
    ];

    /* ──────────────── Category labels (Arabic) ──────────────── */

    const REVENUE_CATEGORIES = [
        'investment_return'       => 'عائد استثمار أموال الصندوق',
        'university_contribution' => 'مساهمة الجامعة',
        'other_revenue'           => 'موارد أخرى',
    ];

    const EXPENSE_CATEGORIES = [
        'admin_expenses'          => 'مصروفات إدارية',
        'investment_management'   => 'تكاليف إدارة استثمارات',
        'new_loan'                => 'صرف قرض جديد',
        'claim_payment'           => 'صرف مطالبة',
        'subscription_refund'     => 'رد اشتراكات / تسويات',
    ];

    /**
     * System-generated categories (created by other controllers, visible in filters).
     */
    const SYSTEM_CATEGORIES = [
        'membership_fees'         => 'رسوم عضوية جديدة',
        'monthly_subscription'    => 'اشتراكات شهرية',
        'subscription'            => 'اشتراك',
        'loan_installment'        => 'سداد قسط قرض',
        'new_loan'                => 'صرف قرض جديد',
        'loan_start'              => 'صرف قرض جديد',
        'early_repayment'         => 'تسوية مبكرة لقرض',
    ];

    /**
     * All category labels combined (for filter display).
     */
    const CATEGORY_LABELS = [
        // Revenue
        'investment_return'       => 'عائد استثمار أموال الصندوق',
        'university_contribution' => 'مساهمة الجامعة',
        'membership_fees'         => 'رسوم عضوية جديدة',
        'monthly_subscription'    => 'اشتراكات شهرية',
        'subscription'            => 'اشتراك',
        'loan_installment'        => 'سداد قسط قرض',
        'early_repayment'         => 'تسوية مبكرة لقرض',
        'other_revenue'           => 'موارد أخرى',
        // Expense
        'admin_expenses'          => 'مصروفات إدارية',
        'investment_management'   => 'تكاليف إدارة استثمارات',
        'new_loan'                => 'صرف قرض جديد',
        'loan_start'              => 'صرف قرض جديد',
        'claim_payment'           => 'صرف مطالبة',
        'subscription_refund'     => 'رد اشتراكات / تسويات',
    ];

    /* ──────────────── Relationships ──────────────── */

    /**
     * Polymorphic reference (subscriptions, loans, etc.).
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Direct membership link (denormalised for fast queries).
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * The employee who created the transaction.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ──────────────── Accessors ──────────────── */

    /**
     * Human-readable transaction number: حركة-1053
     */
    public function getTransactionNumberAttribute(): string
    {
        return 'حركة-' . $this->id;
    }

    /**
     * Arabic label for the category.
     */
    public function getCategoryLabelAttribute(): string
    {
        if ($this->category) {
            return self::CATEGORY_LABELS[$this->category] ?? $this->category;
        }

        if ($this->reference_type) {
            return match ($this->reference_type) {
                'App\\Models\\Financial\\Installment' => self::CATEGORY_LABELS['loan_installment'] ?? 'سداد قسط قرض',
                'App\\Models\\Services\\Subscription' => self::CATEGORY_LABELS['monthly_subscription'] ?? 'اشتراكات شهرية',
                'App\\Models\\Services\\Claim' => self::CATEGORY_LABELS['claim_payment'] ?? 'صرف مطالبة',
                'App\\Models\\Financial\\Loan' => self::CATEGORY_LABELS['new_loan'] ?? 'صرف قرض جديد',
                'App\\Models\\Services\\Membership' => self::CATEGORY_LABELS['membership_fees'] ?? 'رسوم عضوية جديدة',
                default => class_basename($this->reference_type),
            };
        }

        return '-';
    }

    /**
     * Arabic label for the payment method.
     */
    public function getMethodLabelAttribute(): string
    {
        $method = strtolower($this->method ?? '');
        return self::METHOD_LABELS[$method] ?? self::METHOD_LABELS[$this->method] ?? $this->method ?? '-';
    }

    /**
     * Arabic label for the type.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === self::TYPE_IN ? 'إيراد' : 'مصروف';
    }
}
