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
        $todaySubscriptions = Subscription::with('membership.member')
            ->whereDate('created_at', $today)->take(1)
            ->get();

        $dueTodayInstallmentsCount = Installment::where('status', '!=', 'paid')
            ->whereDate('due_date', '<=', $today)
            ->count();
            
        $dueTodayInstallments = Installment::with('loan.membership.member')
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', '<=', $today)
            ->latest('due_date')
            ->take(1)
            ->get();

        $pendingClaimsCount = Claim::where('status', 'pending')->count();
        $pendingClaims = Claim::with('membership.member')
            ->where('status', 'pending')
            ->latest()
            ->take(1)
            ->get();

        // Members with missing signed form (incomplete documents)
        $membersWithMissingDocs = Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->whereDoesntHave('attachments', function($q) {
            $q->where('type', 'signed_form');
        })->with('membershipInfo')->take(1)->get();


        // Total task count for the header
        $totalTasksCount = $todaySubscriptionsCount + $dueTodayInstallmentsCount + $pendingClaimsCount + $membersWithMissingDocs->count();

        return view('dashboard.index', compact(
            'activeMembersCount',
            'todaySubscriptionsCount',
            'todaySubscriptions',
            'dueTodayInstallmentsCount',
            'dueTodayInstallments',
            'pendingClaimsCount',
            'pendingClaims',
            'membersWithMissingDocs',
            'totalTasksCount',
        ));
    }
}
