<?php

namespace App\Http\Controllers;

use App\Models\Membership\Member;
use App\Models\Services\Subscription;
use App\Models\Financial\Installment;
use App\Models\Services\Claim;
use App\Models\System\AuditLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Active members count (status now located dynamically through Membership)
        $activeMembersCount = Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->count();

        // Today's subscriptions created
        $todaySubscriptionsCount = Subscription::whereDate('created_at', $today)->count();

        // Installments due today
        $dueTodayInstallmentsCount = Installment::where('status', '!=', 'paid')
            ->whereDate('due_date', $today)
            ->count();

        // Pending claims
        $pendingClaimsCount = Claim::where('status', 'pending')->count();

        // Recent audit logs
        $auditLogs = AuditLog::with('user')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(20)
            ->get();

        return view('dashboard.index', compact(
            'activeMembersCount',
            'todaySubscriptionsCount',
            'dueTodayInstallmentsCount',
            'pendingClaimsCount',
            'auditLogs'
        ));
    }
}
