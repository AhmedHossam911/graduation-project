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
        'membership_join_fee' => '[{"years":"42 سنة فأكثر","fee_months":"14.68"},{"years":"41 سنة","fee_months":"16.87"},{"years":"40 سنة","fee_months":"19.18"},{"years":"39 سنة","fee_months":"21.59"}]',
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
