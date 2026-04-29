<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use App\Models\System\AuditLog;
use App\Models\System\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubscriptionsExport;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription list with filtering and stats.
     */
    public function index(Request $request)
    {
        $departments = Department::all();

        $query = $this->buildFilteredQuery($request);

        $subscriptions = $query->latest('due_date')->paginate(10)->withQueryString();

        $stats = [
            'month_total' => Subscription::whereMonth('due_date', now()->month)->count(),
            'today_total' => Subscription::whereDate('created_at', now()->toDateString())->count(),
            'late_total'  => Subscription::where('status', 'unpaid')->where('due_date', '<', now())->count(),
        ];

        return view('Membership.index', compact('departments', 'subscriptions', 'stats'));
    }

    /**
     * Show the form for recording a new subscription payment.
     */
    public function create()
    {
        $departments = Department::all();

        // Fetch active memberships for the dropdown
        $memberships = Membership::with('member')
            ->where('status', 'active')
            ->get();

        return view('Membership.create', compact('departments', 'memberships'));
    }

    /**
     * Store a new subscription payment record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
            'amount'        => ['required', 'numeric', 'min:0'],
            'due_date'      => ['required', 'date'],
            'status'        => ['required', 'string', 'in:paid,unpaid'],
        ]);

        $subscription = Subscription::create($validated);

        // Audit log
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'create',
            'table_name' => 'subscriptions',
            'record_id'  => $subscription->id,
            'old_values' => null,
            'new_values' => $subscription->toArray(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم تسجيل الاشتراك بنجاح.');
    }

    /**
     * Export subscriptions to Excel with applied filters.
     */
    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $query->latest('due_date');

        return Excel::download(new SubscriptionsExport($query), 'subscriptions.xlsx');
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    /**
     * Build the filtered subscription query — shared between index() and export().
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Subscription::with(['membership.member.user', 'membership.member.department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('membership.member', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function ($sq) use ($search) {
                      $sq->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department') && $request->department !== 'all') {
            $query->whereHas('membership.member', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return $query;
    }
}
