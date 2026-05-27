<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

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

        $lateInstallments = Installment::with('loan.membership.member')
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', '<', $today)
            ->latest('due_date')
            ->take(5)
            ->get();

        return view('employee.dashboard.index', compact(
            'activeMembersCount',
            'todaySubscriptionsCount',
            'todaySubscriptions',
            'dueTodayInstallmentsCount',
            'dueTodayInstallments',
            'pendingClaimsCount',
            'pendingClaims',
            'membersWithMissingDocs',
            'totalTasksCount',
            'lateInstallments'
        ));
    }

    public function searchMember(\Illuminate\Http\Request $request)
    {
        $search = $request->get('q', '');

        $member = Member::with(['membershipInfo.loans' => function($q) {
            $q->whereIn('status', ['active', 'pending', 'approved']);
        }, 'membershipInfo.subscriptions' => function($q) {
            $q->whereIn('status', ['unpaid', 'overdue'])->orderBy('due_date', 'asc');
        }])->where(function ($q) use ($search) {
            $q->where('full_name', 'LIKE', "%{$search}%")
              ->orWhere('national_id', 'LIKE', "%{$search}%")
              ->orWhereHas('membershipInfo', function ($q2) use ($search) {
                  $q2->where('membership_number', 'LIKE', "%{$search}%");
              });
        })->first();

        if (!$member) {
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على العضو']);
        }

        $membership = $member->membershipInfo;
        if (!$membership) {
            return response()->json(['success' => false, 'message' => 'العضو ليس لديه عضوية مسجلة']);
        }

        // Active Subscriptions
        $unpaidSubscriptions = $membership->subscriptions()->whereIn('status', ['unpaid', 'overdue'])->orderBy('due_date', 'asc')->get();

        // Active Loan
        $activeLoan = $membership->loans->first();
        $unpaidInstallments = collect();
        if ($activeLoan) {
            $unpaidInstallments = $activeLoan->installments()
                ->whereIn('status', ['unpaid', 'overdue'])
                ->orderBy('due_date')
                ->get();
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'full_name' => $member->full_name,
                'national_id' => $member->national_id,
                'membership_id' => $membership->id,
                'membership_number' => $membership->membership_number,
            ],
            'subscriptions' => $unpaidSubscriptions->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'amount' => $sub->amount,
                    'month_year' => \Carbon\Carbon::parse($sub->due_date)->locale('ar')->translatedFormat('F Y'),
                ];
            }),
            'loan' => $activeLoan ? [
                'id' => $activeLoan->id,
                'remaining_amount' => $activeLoan->total_amount - $activeLoan->installments()->where('status', 'paid')->sum('amount'),
                'installments' => $unpaidInstallments->map(function ($inst) {
                    return [
                        'id' => $inst->id,
                        'amount' => $inst->amount,
                        'month_year' => \Carbon\Carbon::parse($inst->due_date)->locale('ar')->translatedFormat('F Y'),
                    ];
                })
            ] : null,
        ]);
    }
}
