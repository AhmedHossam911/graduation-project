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
use App\Traits\DocumentManagerTrait;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    use DocumentManagerTrait;
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

        // Retrieve a list of active memberships to populate the selection dropdown.
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
            // Clear any previous warning or notice timestamps since the member has made a payment.
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
                $q->whereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('national_id', 'like', "%{$search}%");
                  })
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

        $forbiddenStatuses = ['pension_eligible', 'withdrawn', 'dismissed', 'membership_expired', 'suspended'];
        if (in_array($subscription->membership->status, $forbiddenStatuses)) {
            return back()->with('error', 'العضوية مغلقة ولا يمكن سداد اشتراكات عليها.');
        }

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

            // Record the incoming financial transaction for this payment.
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

            // If this payment represents the initial joining fee, generate the schedule for future annual subscriptions.
            if ($subscription->name === 'رسم الاشتراك بالصندوق' || $subscription->membership->subscriptions()->count() === 1) {
                // Activate the membership if it's not active already
                if ($subscription->membership->status !== 'active') {
                    $subscription->membership->update(['status' => 'active']);
                }

                $member = $subscription->membership->member;
                if ($member) {
                    $employmentInfo = $member->employmentInfo;
                    if ($employmentInfo && $employmentInfo->starting_salary) {
                        $annualFee = $employmentInfo->starting_salary * 3;
                        
                        $currentYear = Carbon::now()->year;
                        // Determine the expected retirement year. If not explicitly set, calculate it as 60 years from their birth date.
                        if ($employmentInfo->retirement_date) {
                            $retirementYear = Carbon::parse($employmentInfo->retirement_date)->year;
                        } elseif ($member->birth_date) {
                            $retirementYear = Carbon::parse($member->birth_date)->addYears(60)->year;
                        } else {
                            $retirementYear = $currentYear + 35; // reasonable fallback
                        }

                        // Fetch all currently registered subscription years at once to prevent duplicate entries efficiently.
                        $existingSubscriptions = Subscription::where('membership_id', $subscription->membership_id)
                            ->pluck('name')
                            ->toArray();

                        $newSubscriptions = [];

                        // Create the annual subscription records spanning from the current year up until their retirement year.
                        for ($year = $currentYear; $year <= $retirementYear; $year++) {
                            $subscriptionName = 'اشتراك عام ' . $year;
                            
                            if (!in_array($subscriptionName, $existingSubscriptions)) {
                                $newSubscriptions[] = [
                                    'membership_id' => $subscription->membership_id,
                                    'name'          => $subscriptionName,
                                    'amount'        => $annualFee,
                                    'due_date'      => Carbon::create($year, 7, 1)->startOfDay(),
                                    'status'        => 'unpaid',
                                    'created_at'    => now(),
                                    'updated_at'    => now(),
                                ];
                            }
                        }

                        // Use bulk insert to efficiently save the newly generated subscriptions to the database in a single operation.
                        if (!empty($newSubscriptions)) {
                            Subscription::insert($newSubscriptions);
                        }
                    }
                }
            }

            $user = $subscription->membership->member->user ?? null;
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'تم تسجيل سداد اشتراك',
                    'message' => 'تم تسجيل سداد اشتراكك (' . $subscription->name . ') بنجاح.',
                ]);
            }
        });

        return back()->with('success', 'تم تسجيل سداد الاشتراك بنجاح.');
    }

    /**
     * Send formal notification for overdue subscription (Modal 8)
     */
    public function notify(Request $request, Subscription $subscription)
    {
        if ($subscription->status === 'unpaid' && \Carbon\Carbon::parse($subscription->due_date)->addMonths(6)->isPast() && is_null($subscription->notice_sent_at)) {
            $subscription->update([
                'notice_sent_at' => now()
            ]);

            $user = $subscription->membership->member->user ?? null;
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'إخطار رسمي بتأخر السداد',
                    'message' => 'نحيطكم علماً بضرورة سداد الاشتراك المستحق لتجنب إيقاف العضوية.',
                ]);
            }

            return back()->with('success', 'تم إرسال الإخطار المسجل وتحديث الحالة.');
        }

        return back()->with('error', 'لا يمكن إرسال الإخطار لهذا الاشتراك. قد لا يكون متأخراً أكثر من 6 أشهر أو تم إرسال إخطار مسبقاً.');
    }

    public function viewReceipt(Subscription $subscription)
    {
        $transaction = \App\Models\Financial\Transaction::where('reference_type', Subscription::class)
            ->where('reference_id', $subscription->id)
            ->whereNotNull('attachment_path')
            ->first();

        if (!$transaction) {
            return back()->with('error', 'لا يوجد إيصال متاح لهذا الاشتراك.');
        }

        $subscription->load('membership.member');
        $memberName = $subscription->membership->member->user->name ?? 'عضو';
        $subName = $subscription->name ?? 'اشتراك';
        $fileName = "{$memberName} - {$subName}";

        return $this->sendDocumentResponse(storage_path('app/public/' . $transaction->attachment_path), $fileName, false);
    }

    public function downloadReceipt(Subscription $subscription)
    {
        $transaction = \App\Models\Financial\Transaction::where('reference_type', Subscription::class)
            ->where('reference_id', $subscription->id)
            ->whereNotNull('attachment_path')
            ->first();

        if (!$transaction) {
            return back()->with('error', 'لا يوجد إيصال متاح لهذا الاشتراك.');
        }

        $subscription->load('membership.member');
        $memberName = $subscription->membership->member->user->name ?? 'عضو';
        $subName = $subscription->name ?? 'اشتراك';
        $fileName = "{$memberName} - {$subName}";

        return $this->sendDocumentResponse(storage_path('app/public/' . $transaction->attachment_path), $fileName, true);
    }
}
