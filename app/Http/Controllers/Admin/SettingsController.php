<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\SystemSetting;
use App\Models\System\AuditLog;

class SettingsController extends Controller
{
    /**
     * Default settings values.
     */
    protected $defaults = [
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
     * Display the settings page.
     */
    public function index()
    {
        // Load all settings
        $settings = [];
        foreach ($this->defaults as $key => $default) {
            $settings[$key] = SystemSetting::where('key', $key)->first()?->value ?? $default;
        }

        // Get the latest system setting update for audit display
        $lastUpdate = SystemSetting::orderBy('updated_at', 'desc')->first();
        $lastUpdateUser = null;
        if ($lastUpdate) {
            $log = AuditLog::with('user')
                ->where('action', 'like', '%إعدادات%')
                ->orderBy('created_at', 'desc')
                ->first();
            if ($log && $log->user) {
                $lastUpdateUser = $log->user->name;
            }
        }

        return view('admin.settings.index', compact('settings', 'lastUpdate', 'lastUpdateUser'));
    }

    /**
     * Update settings in the database.
     */
    public function update(Request $request)
    {
        $rules = [
            'system_name' => 'required|string|max:255',
            'retirement_age' => 'required|integer|min:40|max:80',
            'default_currency' => 'required|string|max:50',
            'subscription_amount' => 'required|numeric|min:0',
            'membership_join_fee' => 'required|numeric|min:0',
            'membership_min_age' => 'required|integer|min:18|max:100',
            'membership_max_age' => 'required|integer|min:18|max:100|gte:membership_min_age',
            'loan_percentage' => 'required|numeric|min:0|max:100',
            'loan_interest_rate' => 'required|numeric|min:0|max:100',
            'loan_max_amount' => 'required|numeric|min:0',
            'loan_repayment_months' => 'required|integer|min:1|max:120',
            'loan_min_years_subscribed' => 'required|integer|min:0|max:50',
            'claim_basic_percentage' => 'required|numeric|min:0',
            'claim_transfer_resignation_percentage' => 'required|numeric|min:0|max:100',
            'claim_funeral_expenses' => 'required|numeric|min:0',
            'claim_min_years_subscribed' => 'required|integer|min:0|max:50',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Log the action in AuditLog if the audit log system is functional
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'تحديث إعدادات النظام اللائحية والمالية',
                'table_name' => 'system_settings',
                'new_values' => $validated,
                'ip_address' => $request->ip()
            ]);
        } catch (\Exception $e) {
            // Silence log errors if table or fields differ
        }

        return redirect()->back()->with('success', 'تم حفظ التعديلات بنجاح.');
    }

    /**
     * Reset settings to basic defaults.
     */
    public function reset(Request $request)
    {
        foreach ($this->defaults as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'استعادة قيم اللائحة الأساسية للإعدادات',
                'table_name' => 'system_settings',
                'new_values' => $this->defaults,
                'ip_address' => $request->ip()
            ]);
        } catch (\Exception $e) {
            // Silence log errors
        }

        return redirect()->back()->with('success', 'تم استعادة قيم اللائحة الأساسية بنجاح.');
    }
}
