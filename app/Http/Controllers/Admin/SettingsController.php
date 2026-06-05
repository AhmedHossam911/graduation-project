<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\System\SystemSetting;
use App\Models\System\AuditLog;

class SettingsController extends Controller
{
    // Defaults are now managed in SystemSetting::$defaults

    /**
     * Display the settings page.
     */
    public function index()
    {
        // We load all settings efficiently from the cache using a single query to reduce database load.
        $dbSettings = Cache::rememberForever('system_settings', function () {
            return SystemSetting::pluck('value', 'key')->toArray();
        });

        $settings = array_merge(SystemSetting::$defaults, $dbSettings);

        // Retrieve the most recent update made to the system settings to display in the audit trail.
        $lastUpdate = SystemSetting::orderBy('updated_at', 'desc')->first();
        $lastUpdateUser = null;
        if ($lastUpdate) {
            $log = AuditLog::with('user:id,name')
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
            // 'subscription_percentage' => 'required|numeric|min:0|max:100',
            // 'employer_contribution_percentage' => 'required|numeric|min:0|max:100',
            'membership_join_fee' => 'required|string',
            'membership_min_age' => 'required|integer|min:18|max:100',
            'membership_max_age' => 'required|integer|min:18|max:100|gte:membership_min_age',
            'dismissal_notice_months' => 'required|integer|min:1|max:12',
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

        Cache::forget('system_settings');

        // Attempt to log the update action in the AuditLog to maintain a record of system changes.
        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'تحديث إعدادات النظام اللائحية والمالية',
                'table_name' => 'system_settings',
                'new_values' => $validated,
                'ip_address' => $request->ip()
            ]);
        } catch (\Exception $e) {
            // We silently catch any exceptions here to ensure that a logging failure doesn't interrupt the user's workflow.
        }

        return redirect()->back()->with('success', 'تم حفظ التعديلات بنجاح.');
    }

    /**
     * Reset settings to basic defaults.
     */
    public function reset(Request $request)
    {
        foreach (SystemSetting::$defaults as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('system_settings');

        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'استعادة قيم اللائحة الأساسية للإعدادات',
                'table_name' => 'system_settings',
                'new_values' => SystemSetting::$defaults,
                'ip_address' => $request->ip()
            ]);
        } catch (\Exception $e) {
        }

        return redirect()->back()->with('success', 'تم استعادة قيم اللائحة الأساسية بنجاح.');
    }
}
