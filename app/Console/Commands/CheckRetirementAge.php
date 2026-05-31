<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership\Member;
use App\Models\System\SystemSetting;
use Carbon\Carbon;
use App\Mail\RetirementAgeReachedMail;
use App\Notifications\RetirementAgeReachedNotification;
use Illuminate\Support\Facades\Mail;

class CheckRetirementAge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:check-retirement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check members who reached retirement age today and update their membership status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $retirementAge = (int) SystemSetting::get('retirement_age', 60);
        $targetDate = Carbon::today()->subYears($retirementAge)->format('m-d');
        $targetYear = Carbon::today()->subYears($retirementAge)->year;

        // Find members whose birthdate implies they just reached retirement age today.
        $members = Member::with(['membershipInfo', 'user'])
            ->whereMonth('date_of_birth', Carbon::today()->month)
            ->whereDay('date_of_birth', Carbon::today()->day)
            ->whereYear('date_of_birth', '<=', $targetYear) // They are retirementAge or older
            ->whereHas('membershipInfo', function ($query) {
                $query->whereNotIn('status', ['membership_expired', 'withdrawn', 'dismissed', 'suspended']);
            })
            ->get();

        $count = 0;
        foreach ($members as $member) {
            // Check actual age
            if (Carbon::parse($member->date_of_birth)->age >= $retirementAge) {
                $member->membershipInfo->update([
                    'status' => 'membership_expired'
                ]);

                $user = $member->user;
                if ($user) {
                    $user->notify(new RetirementAgeReachedNotification($member));
                    
                    if ($user->email) {
                        try {
                            Mail::to($user->email)->send(new RetirementAgeReachedMail($member));
                        } catch (\Exception $e) {
                            \Log::error('Failed to send retirement mail: ' . $e->getMessage());
                        }
                    }
                }
                $count++;
            }
        }

        $this->info("Checked retirement age. Updated {$count} memberships to expired.");
    }
}
