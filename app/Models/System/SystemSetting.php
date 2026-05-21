<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Default settings values.
     */
    public static $defaults = [
        'system_name' => 'صندوق التأمين الخاص لأعضاء هيئة التدريس والعاملين بجامعة العاصمة',
        'retirement_age' => '60',
        'default_currency' => 'جنيه مصري (EGP)',
        'subscription_amount' => '100',
        'membership_join_fee' => '500',
        'membership_min_age' => '21',
        'membership_max_age' => '59',
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
     * Get a setting value by key, fallback to defaults or user specified fallback.
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
