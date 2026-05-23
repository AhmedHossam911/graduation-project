<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Membership\Member;
use App\Models\Services\Subscription;
use App\Models\Financial\Installment;
use App\Models\Services\Claim;
use App\Models\Services\Loan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        if (!$member || !$member->membershipInfo) {
            return view('member.dashboard.index', ['member' => null]);
        }

        $membership = $member->membershipInfo;

        // Paid Subscriptions
        $paidSubscriptionsCount = Subscription::where('membership_id', $membership->id)
            ->where('status', 'paid')
            ->count();
        $totalPaidSubscriptions = Subscription::where('membership_id', $membership->id)
            ->where('status', 'paid')
            ->sum('amount');

        // Loans
        $activeLoansCount = Loan::where('membership_id', $membership->id)
            ->where('status', 'active')
            ->count();
            
        // Installments
        $dueInstallments = Installment::whereHas('loan', function($q) use ($membership) {
                $q->where('membership_id', $membership->id);
            })
            ->where('status', '!=', 'paid')
            ->orderBy('due_date', 'asc')
            ->get();
            
        $nextInstallment = $dueInstallments->first();
        $overdueInstallmentsCount = $dueInstallments->filter(function ($inst) {
            return Carbon::parse($inst->due_date)->startOfDay()->isBefore(Carbon::today());
        })->count();

        // Claims
        $pendingClaimsCount = Claim::where('membership_id', $membership->id)
            ->where('status', 'pending')
            ->count();
            
        $claims = Claim::where('membership_id', $membership->id)
            ->latest()
            ->take(3)
            ->get();

        return view('member.dashboard.index', compact(
            'member',
            'membership',
            'paidSubscriptionsCount',
            'totalPaidSubscriptions',
            'activeLoansCount',
            'nextInstallment',
            'overdueInstallmentsCount',
            'pendingClaimsCount',
            'claims'
        ));
    }
}
