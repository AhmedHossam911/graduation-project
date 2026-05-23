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

        // 1. Send first warning (1 month overdue)
        $firstWarningSubs = \App\Models\Services\Subscription::with('membership.member.user')
            ->where('status', 'unpaid')
            ->where('due_date', '<=', $oneMonthAgo)
            ->whereNull('first_warning_sent_at')
            ->get();

        foreach ($firstWarningSubs as $sub) {
            $user = $sub->membership->member->user;
            if ($user && $user->email) {
                // In a real app, use Mail::to($user)->send(...)
                // \Illuminate\Support\Facades\Mail::raw('يرجى سداد اشتراكك المتأخر لمدة شهر.', function($msg) use ($user) {
                //    $msg->to($user->email)->subject('تأخر سداد الاشتراك');
                // });
            }
            $sub->update(['first_warning_sent_at' => $now]);
        }

        // 2. Send second warning (6 months overdue)
        $secondWarningSubs = \App\Models\Services\Subscription::with('membership.member.user')
            ->where('status', 'unpaid')
            ->where('due_date', '<=', $sixMonthsAgo)
            ->whereNull('second_warning_sent_at')
            ->get();

        foreach ($secondWarningSubs as $sub) {
            $user = $sub->membership->member->user;
            if ($user && $user->email) {
                // \Illuminate\Support\Facades\Mail::raw('اشتراكك متأخر لمدة 6 أشهر. سيتم إرسال إخطار مسجل.', function($msg) use ($user) {
                //    $msg->to($user->email)->subject('تحذير: تأخر سداد الاشتراك 6 أشهر');
                // });
            }
            $sub->update(['second_warning_sent_at' => $now]);
        }

        // 3. Suspend memberships if 1 month passed since notice_sent_at
        $suspendSubs = \App\Models\Services\Subscription::with('membership.member')
            ->where('status', 'unpaid')
            ->whereNotNull('notice_sent_at')
            ->where('notice_sent_at', '<=', $oneMonthAgo)
            ->get();

        foreach ($suspendSubs as $sub) {
            $membership = $sub->membership;
            if ($membership && $membership->status !== 'suspended') {
                $membership->update(['status' => 'suspended']);

                // Notify admin/employee
                $employees = \App\Models\System\User::whereHas('roles', function($q) {
                    $q->whereIn('name', ['General Admin', 'Membership Employee']);
                })->get();

                foreach ($employees as $emp) {
                    $emp->notify(new \App\Notifications\MembershipSuspendedNotification($membership));
                }
            }
        }

        $this->info('Overdue subscriptions checked successfully.');
    }
}
