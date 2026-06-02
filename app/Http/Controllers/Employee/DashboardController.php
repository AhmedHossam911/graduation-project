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
        if (auth()->check() && auth()->user()->role && strtolower(auth()->user()->role->name) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $today = Carbon::today();

        // Gather key statistics to display on the dashboard summary cards.
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

        // Identify active members who have not yet uploaded their signed membership form (incomplete documentation).
        $membersWithMissingDocs = Member::whereHas('membershipInfo', function($q) {
            $q->where('status', 'active');
        })->whereDoesntHave('attachments', function($q) {
            $q->where('type', 'signed_form');
        })->with('membershipInfo')->take(1)->get();


        // Calculate the total number of pending tasks to display a notification badge in the header.
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
        $memberId = $request->get('member_id');
        $type = $request->get('type'); // subscription, installment, claim, global

        $query = Member::with(['user', 'membershipInfo.loans' => function($q) {
            $q->whereIn('status', ['active', 'pending', 'approved']);
        }, 'membershipInfo.loans.installments' => function($q) {
            $q->whereIn('status', ['unpaid', 'overdue'])->orderBy('due_date', 'asc');
        }, 'membershipInfo.subscriptions' => function($q) {
            $q->whereIn('status', ['unpaid', 'overdue'])->orderBy('due_date', 'asc');
        }]);

        // Filter based on the requested type
        if ($type === 'subscription') {
            $query->whereHas('membershipInfo.subscriptions', function($q) {
                $q->whereIn('status', ['unpaid', 'overdue']);
            });
        } elseif ($type === 'installment') {
            $query->whereHas('membershipInfo.loans', function($q) {
                $q->whereIn('status', ['active', 'pending', 'approved'])
                  ->whereHas('installments', function($q2) {
                      $q2->whereIn('status', ['unpaid', 'overdue']);
                  });
            });
        }

        if ($memberId) {
            $member = $query->find($memberId);
        } else {
            $member = $query->where(function ($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('national_id', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('membershipInfo', function ($q2) use ($search) {
                      $q2->where('membership_number', 'LIKE', "%{$search}%");
                  });
            })->first();
        }

        if (!$member) {
            if ($type === 'subscription') {
                return response()->json(['success' => false, 'message' => 'لم يتم العثور على عضو لديه اشتراكات مستحقة بالبيانات المدخلة']);
            } elseif ($type === 'installment') {
                return response()->json(['success' => false, 'message' => 'لم يتم العثور على عضو لديه قروض مستحقة بالبيانات المدخلة']);
            }
            return response()->json(['success' => false, 'message' => 'لم يتم العثور على العضو']);
        }

        $membership = $member->membershipInfo;
        if (!$membership) {
            return response()->json(['success' => false, 'message' => 'العضو ليس لديه عضوية مسجلة']);
        }

        // Retrieve all active and unpaid subscriptions for this member.
        $unpaidSubscriptions = $membership->subscriptions;

        // Fetch the member's currently active loan and any unpaid installments associated with it.
        $activeLoan = $membership->loans->first();
        $unpaidInstallments = collect();
        if ($activeLoan) {
            // Get only the first upcoming installment for loans
            $firstInstallment = $activeLoan->installments->first();
            if ($firstInstallment) {
                $unpaidInstallments->push($firstInstallment);
            }
        }

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'full_name' => $member->user ? $member->user->name : 'غير معروف',
                'national_id' => $member->user ? $member->user->national_id : '-',
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
                'remaining_amount' => $activeLoan->total_amount - $activeLoan->installments->where('status', 'paid')->sum('amount'),
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
