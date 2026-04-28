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

        // Stats for the cards
        $activeMembersCount = Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->count();

        $todaySubscriptionsCount = Subscription::whereDate('created_at', $today)->count();

        $dueTodayInstallmentsCount = Installment::where('status', '!=', 'paid')
            ->whereDate('due_date', $today)
            ->count();

        $pendingClaimsCount = Claim::where('status', 'pending')->count();

        // Recent activities (Audit Logs)
        // We try to eager load user and if possible member (though AuditLog doesn't have direct member relation, 
        // we can handle it in the view or controller if needed).
        $auditLogs = AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Enhance audit logs with member names if related to members table
        foreach ($auditLogs as $log) {
            $log->member_name = '-';
            $log->membership_number = '-';
            
            if ($log->table_name === 'members') {
                $member = Member::with('membershipInfo')->find($log->record_id);
                if ($member) {
                    $log->member_name = $member->full_name;
                    $log->membership_number = $member->membershipInfo->membership_number ?? '-';
                }
            } elseif ($log->table_name === 'memberships') {
                $membership = \App\Models\Services\Membership::with('member')->find($log->record_id);
                if ($membership && $membership->member) {
                    $log->member_name = $membership->member->full_name;
                    $log->membership_number = $membership->membership_number;
                }
            }
        }

        return view('dashboard.index', compact(
            'activeMembersCount',
            'todaySubscriptionsCount',
            'dueTodayInstallmentsCount',
            'pendingClaimsCount',
            'auditLogs'
        ));
    }
}
