<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Console Command: subscriptions:check-overdue
 * 
 * A critical scheduled task that enforces the fund's bylaws regarding unpaid subscriptions.
 * It automatically transitions unpaid dues to overdue, sends escalation warnings via email,
 * and ultimately suspends memberships if arrears persist beyond the grace period.
 */
class CheckOverdueSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check overdue subscriptions and send warnings or suspend memberships.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $oneMonthAgo = $now->copy()->subMonth();
        $sixMonthsAgo = $now->copy()->subMonths(6);

        // 0. Update status to 'overdue' if due_date has passed
        // This ensures the dashboard accurately reflects late payments.
        \App\Models\Services\Subscription::where('status', 'unpaid')
            ->where('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        // 1. Send late payment warnings.
        // Target subscriptions that are between 1 and 6 months late.
        // Ensures we only send one warning per month (grace period between emails).
        $warningSubs = \App\Models\Services\Subscription::with('membership.member.user')
            ->whereIn('status', ['unpaid', 'overdue'])
            ->where('due_date', '<=', $oneMonthAgo)
            ->where('due_date', '>', $sixMonthsAgo)
            ->where(function ($query) use ($now) {
                $query->whereNull('last_warning_sent_at')
                      ->orWhere('last_warning_sent_at', '<=', $now->copy()->subDays(30));
            })
            ->get();

        foreach ($warningSubs as $sub) {
            $user = $sub->membership->member->user;
            if ($user && $user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LatePaymentReminderMail($sub));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send late payment reminder to ' . $user->email . ': ' . $e->getMessage());
                }
            }
            if ($user) {
                \App\Models\Auth\Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'تأخر سداد الاشتراك',
                    'message' => 'لديك اشتراك متأخر الدفع بقيمة ' . number_format($sub->amount, 2) . ' يرجى سداده لتجنب إيقاف العضوية.',
                ]);
            }
            $sub->update(['last_warning_sent_at' => $now]);
        }

        // 2. Suspend Memberships.
        // According to bylaws, if an official notice has been sent and another month passes without payment,
        // the membership is automatically suspended.
        $suspendSubs = \App\Models\Services\Subscription::with('membership.member.user')
            ->whereIn('status', ['unpaid', 'overdue'])
            ->whereNotNull('notice_sent_at')
            ->where('notice_sent_at', '<=', $oneMonthAgo)
            ->get();

        foreach ($suspendSubs as $sub) {
            $membership = $sub->membership;
            if ($membership && $membership->status !== 'suspended') {
                // Execute the suspension
                $membership->update(['status' => 'suspended']);

                $user = $membership->member->user;
                if ($user && $user->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\MembershipSuspendedMail($membership));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send membership suspended mail to ' . $user->email . ': ' . $e->getMessage());
                    }
                }

                // Notify the administration team about the automated suspension so they can take further manual action if needed.
                $employees = \App\Models\Auth\User::whereHas('roles', function($q) {
                    $q->whereIn('name', ['General Admin', 'Membership Employee']);
                })->get();

                foreach ($employees as $emp) {
                    \App\Models\Auth\Notification::create([
                        'user_id' => $emp->id,
                        'title'   => 'إيقاف عضوية تلقائي',
                        'message' => 'تم إيقاف العضوية رقم ' . $membership->membership_number . ' تلقائياً بسبب تأخر السداد.',
                    ]);
                }

                if ($user) {
                    \App\Models\Auth\Notification::create([
                        'user_id' => $user->id,
                        'title'   => 'تم إيقاف العضوية',
                        'message' => 'تم إيقاف عضويتك تلقائياً لتأخرك في السداد، يرجى مراجعة إدارة الصندوق.',
                    ]);
                }
            }
        }

        $this->info('Overdue subscriptions checked successfully.');
    }
}
