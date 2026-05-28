<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        \App\Models\Services\Subscription::where('status', 'unpaid')
            ->where('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        // 1. Send warning (1 to 6 months overdue)
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
            $sub->update(['last_warning_sent_at' => $now]);
        }

        // 2. Suspend memberships if 1 month passed since official notice (notice_sent_at)
        $suspendSubs = \App\Models\Services\Subscription::with('membership.member.user')
            ->whereIn('status', ['unpaid', 'overdue'])
            ->whereNotNull('notice_sent_at')
            ->where('notice_sent_at', '<=', $oneMonthAgo)
            ->get();

        foreach ($suspendSubs as $sub) {
            $membership = $sub->membership;
            if ($membership && $membership->status !== 'suspended') {
                $membership->update(['status' => 'suspended']);

                $user = $membership->member->user;
                if ($user && $user->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\MembershipSuspendedMail($membership));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send membership suspended mail to ' . $user->email . ': ' . $e->getMessage());
                    }
                }

                // Notify admin/employee
                $employees = \App\Models\Auth\User::whereHas('roles', function($q) {
                    $q->whereIn('name', ['General Admin', 'Membership Employee']);
                })->get();

                foreach ($employees as $emp) {
                    if (class_exists(\App\Notifications\MembershipSuspendedNotification::class)) {
                        $emp->notify(new \App\Notifications\MembershipSuspendedNotification($membership));
                    }
                }
            }
        }

        $this->info('Overdue subscriptions checked successfully.');
    }
}
