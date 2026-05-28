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
                    'last_warning_sent_at' => null,
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
                    $query->whereIn('status', ['unpaid', 'overdue'])
                          ->where('due_date', '>=', now()->startOfDay());
                } elseif ($status === 'overdue_0_6') {
                    $query->whereIn('status', ['unpaid', 'overdue'])
                          ->where('due_date', '<', now()->startOfDay())
                          ->where('due_date', '>=', now()->subMonths(6)->startOfDay());
                } elseif ($status === 'overdue_6_no_notice') {
                    $query->whereIn('status', ['unpaid', 'overdue'])
                          ->where('due_date', '<', now()->subMonths(6)->startOfDay())
                          ->whereNull('notice_sent_at');
                } elseif ($status === 'overdue_6_notice') {
                    $query->whereIn('status', ['unpaid', 'overdue'])
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
            'payment_method' => 'required|in:cash,bank_transfer,salary_deduction,university_payment_order',
            'receipt_number' => 'required|string|max:255',
            'receipt_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $subscription->load('membership');
        $oldValues = $subscription->toArray();

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $subscription, $oldValues) {
            $subscription->update([
                'status' => 'paid',
                'last_warning_sent_at' => null,
                'notice_sent_at' => null,
            ]);

            $path = null;
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

            // Create Transaction
            \App\Models\Financial\Transaction::create([
                'membership_id' => $subscription->membership_id,
                'reference_type' => \App\Models\Services\Subscription::class,
                'reference_id' => $subscription->id,
                'amount' => $subscription->amount,
                'type' => 'IN',
                'method' => $request->payment_method,
                'category' => 'subscription',
                'receipt_no' => $request->receipt_number,
                'attachment_path' => $path,
                'created_by' => auth()->id(),
            ]);

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

            // Generate future annual subscriptions if this is the join fee
            if ($subscription->name === 'رسم الاشتراك بالصندوق' || $subscription->membership->subscriptions()->count() === 1) {
                $member = $subscription->membership->member;
                if ($member) {
                    $employmentInfo = $member->employmentInfo;
                    if ($employmentInfo && $employmentInfo->starting_salary) {
                        $annualFee = $employmentInfo->starting_salary * 3;
                        
                        $currentYear = Carbon::now()->year;
                        // Determine retirement year, fallback to 60 years after birth if missing
                        if ($employmentInfo->retirement_date) {
                            $retirementYear = Carbon::parse($employmentInfo->retirement_date)->year;
                        } elseif ($member->birth_date) {
                            $retirementYear = Carbon::parse($member->birth_date)->addYears(60)->year;
                        } else {
                            $retirementYear = $currentYear + 35; // reasonable fallback
                        }

                        // Generate subscriptions for every year until retirement
                        for ($year = $currentYear; $year <= $retirementYear; $year++) {
                            $subscriptionName = 'اشتراك عام ' . $year;
                            
                            // Check if subscription for this year already exists
                            $exists = Subscription::where('membership_id', $subscription->membership_id)
                                ->where('name', $subscriptionName)
                                ->exists();

                            if (!$exists) {
                                Subscription::create([
                                    'membership_id' => $subscription->membership_id,
                                    'name'          => $subscriptionName,
                                    'amount'        => $annualFee,
                                    'due_date'      => Carbon::create($year, 7, 1)->startOfDay(),
                                    'status'        => 'unpaid',
                                ]);
                            }
                        }
                    }
                }
            }
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

    public function viewReceipt(Subscription $subscription)
    {
        $transaction = \App\Models\Financial\Transaction::where('reference_type', Subscription::class)
            ->where('reference_id', $subscription->id)
            ->whereNotNull('attachment_path')
            ->first();

        if (!$transaction || !file_exists(storage_path('app/public/' . $transaction->attachment_path))) {
            return back()->with('error', 'لا يوجد إيصال متاح لهذا الاشتراك.');
        }

        $subscription->load('membership.member');
        $memberName = $subscription->membership->member->full_name ?? 'عضو';
        $subName = $subscription->name ?? 'اشتراك';
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', "{$memberName} - {$subName}");
        $extension = pathinfo($transaction->attachment_path, PATHINFO_EXTENSION);

        return response()->file(storage_path('app/public/' . $transaction->attachment_path), [
            'Content-Disposition' => 'inline; filename="' . $fileName . '.' . $extension . '"'
        ]);
    }

    public function downloadReceipt(Subscription $subscription)
    {
        $transaction = \App\Models\Financial\Transaction::where('reference_type', Subscription::class)
            ->where('reference_id', $subscription->id)
            ->whereNotNull('attachment_path')
            ->first();

        if (!$transaction || !file_exists(storage_path('app/public/' . $transaction->attachment_path))) {
            return back()->with('error', 'لا يوجد إيصال متاح لهذا الاشتراك.');
        }

        $subscription->load('membership.member');
        $memberName = $subscription->membership->member->full_name ?? 'عضو';
        $subName = $subscription->name ?? 'اشتراك';
        // Clean filename of invalid characters
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', "{$memberName} - {$subName}");
        $extension = pathinfo($transaction->attachment_path, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/public/' . $transaction->attachment_path), "{$fileName}.{$extension}");
    }
}
