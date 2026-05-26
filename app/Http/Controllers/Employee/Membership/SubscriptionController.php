<?php

namespace App\Http\Controllers\Employee\Membership;

use App\Http\Controllers\Controller;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use App\Models\System\AuditLog;
use App\Models\System\Department;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubscriptionsExport;
use Carbon\Carbon;

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

        return view('employee.membership.index', compact('departments', 'subscriptions', 'stats'));
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

        if ($validated['status'] === 'paid') {
            // Reset warning counters for this membership's unpaid subscriptions
            Subscription::where('membership_id', $validated['membership_id'])
                ->where('status', 'unpaid')
                ->update([
                    'first_warning_sent_at' => null,
                    'second_warning_sent_at' => null,
                    'notice_sent_at' => null,
                ]);
        }

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

    /**
     * Send registered notice for a subscription.
     */
    public function sendNotice($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->addMonths(6)->isPast() && is_null($subscription->notice_sent_at)) {
            $subscription->update([
                'notice_sent_at' => now()
            ]);

            return back()->with('success', 'تم إرسال الإخطار المسجل وتحديث الحالة.');
        }

        return back()->with('error', 'لا يمكن إرسال الإخطار لهذا الاشتراك.');
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
            $status = $request->status;

            if ($status === 'suspended') {
                $query->whereHas('membership', function ($q) {
                    $q->where('status', 'suspended');
                });
            } else {
                $query->whereHas('membership', function ($q) {
                    $q->where('status', '!=', 'suspended');
                });

                if ($status === 'paid') {
                    $query->where('status', 'paid');
                } elseif ($status === 'unpaid') {
                    $query->where('status', 'unpaid')
                          ->where('due_date', '>=', now()->startOfDay());
                } elseif ($status === 'overdue_0_6') {
                    $query->where('status', 'unpaid')
                          ->where('due_date', '<', now()->startOfDay())
                          ->where('due_date', '>=', now()->subMonths(6)->startOfDay());
                } elseif ($status === 'overdue_6_no_notice') {
                    $query->where('status', 'unpaid')
                          ->where('due_date', '<', now()->subMonths(6)->startOfDay())
                          ->whereNull('notice_sent_at');
                } elseif ($status === 'overdue_6_notice') {
                    $query->where('status', 'unpaid')
                          ->where('due_date', '<', now()->subMonths(6)->startOfDay())
                          ->whereNotNull('notice_sent_at');
                } else {
                    $query->where('status', $status);
                }
            }
        }

        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->date)->toDateString();
            } catch (\Exception $exception) {
                try {
                    $date = Carbon::parse($request->date)->toDateString();
                } catch (\Exception $fallbackException) {
                    $date = null;
                }
            }

            if ($date) {
                $query->whereDate('due_date', $date);
            }
        }

        return $query;
    }

    public function pay(Request $request, Subscription $subscription)
    {
        $request->validate([
            'receipt_number' => 'required|string|max:255',
            'receipt_image' => 'nullable|image|max:5120',
        ]);

        $subscription->load('membership');
        $oldValues = $subscription->toArray();

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $subscription, $oldValues) {
            $subscription->update([
                'status' => 'paid',
                'first_warning_sent_at' => null,
                'second_warning_sent_at' => null,
                'notice_sent_at' => null,
            ]);

            if ($request->hasFile('receipt_image')) {
                $memberId = $subscription->membership->member_id;
                $path = $request->file('receipt_image')
                    ->store("members/{$memberId}/subscriptions/{$subscription->id}", 'public');

                \App\Models\Membership\Attachment::create([
                    'member_id' => $memberId,
                    'type'      => "subscription_{$subscription->id}_receipt",
                    'file_path' => $path,
                ]);
            }

            // Audit log
            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'payment',
                'table_name' => 'subscriptions',
                'record_id'  => $subscription->id,
                'old_values' => $oldValues,
                'new_values' => $subscription->fresh()->toArray(),
                'ip_address' => request()->ip(),
            ]);
        });

        return back()->with('success', 'تم تسجيل سداد الاشتراك بنجاح.');
    }

    /**
     * Send formal notification for overdue subscription (Modal 8)
     */
    public function notify(Request $request, Subscription $subscription)
    {
        // Add logic to check conditions and send formal notice
        // ... backend logic here ...

        return back()->with('success', 'تم إرسال الإخطار بنجاح.');
    }
}
