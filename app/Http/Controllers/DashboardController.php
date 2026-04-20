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

        // Card 1: Active members count
        $activeMembersCount = Member::where('status', 'active')->count();

        // Card 2: Today's subscriptions (payments/subscriptions created today)
        $todaySubscriptionsCount = Subscription::whereDate('created_at', $today)->count();

        // Card 3: Installments due today
        $dueTodayInstallmentsCount = Installment::where('status', '!=', 'paid')
            ->whereDate('due_date', $today)
            ->count();

        // Card 4: Pending claims (under review)
        $pendingClaimsCount = Claim::where('status', 'pending')->count();

        // Recent audit logs (today's operations) with the user who performed them
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
