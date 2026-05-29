<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * A predefined set of default configuration values used when the database lacks a specific setting.
     */
    public static $defaults = [
        'system_name' => 'صندوق التأمين الخاص لأعضاء هيئة التدريس والعاملين بجامعة العاصمة',
        'retirement_age' => '60',
        'default_currency' => 'جنيه مصري (EGP)',
        'subscription_percentage' => '10',
        'employer_contribution_percentage' => '10',
        'membership_join_fee' => '[{"years":"42","fee_months":"14.68"},{"years":"41","fee_months":"16.87"},{"years":"40","fee_months":"19.18"},{"years":"39","fee_months":"21.59"},{"years":"38","fee_months":"24.10"},{"years":"37","fee_months":"26.73"},{"years":"36","fee_months":"29.47"},{"years":"35","fee_months":"32.32"},{"years":"34","fee_months":"35.28"},{"years":"33","fee_months":"38.36"},{"years":"32","fee_months":"41.54"},{"years":"31","fee_months":"44.83"},{"years":"30","fee_months":"48.22"},{"years":"29","fee_months":"51.71"},{"years":"28","fee_months":"55.29"},{"years":"27","fee_months":"58.95"},{"years":"26","fee_months":"62.68"},{"years":"25","fee_months":"66.46"},{"years":"24","fee_months":"70.28"},{"years":"23","fee_months":"74.13"},{"years":"22","fee_months":"77.99"},{"years":"21","fee_months":"81.85"},{"years":"20","fee_months":"85.67"},{"years":"19","fee_months":"89.45"},{"years":"18","fee_months":"93.16"},{"years":"17","fee_months":"96.79"},{"years":"16","fee_months":"98.99"},{"years":"15","fee_months":"102.47"}]',
        'membership_min_age' => '21',
        'membership_max_age' => '59',
        'dismissal_notice_months' => '3',
        'loan_percentage' => '75',
        'loan_interest_rate' => '8',
        'loan_max_amount' => '20000',
        'loan_repayment_months' => '36',
        'loan_min_years_subscribed' => '5',
        'claim_basic_percentage' => '145',
        'claim_transfer_resignation_percentage' => '80',
        'claim_funeral_expenses' => '3448',
        'claim_min_years_subscribed' => '10',
    ];

    /**
     * Retrieve a specific configuration setting by its key.
     * If the setting is missing from the database, it gracefully falls back to the system defaults or a provided fallback.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting !== null && $setting->value !== null) {
            return $setting->value;
        }

        return self::$defaults[$key] ?? $default;
    }
}
