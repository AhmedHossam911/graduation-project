<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use Carbon\Carbon;

/**
 * Console Command: subscriptions:generate-annual
 * 
 * Scheduled task that generates the mandatory annual subscription fee for all active members.
 * The fee is dynamically calculated based on the member's starting salary as per the fund's financial rules.
 */
class GenerateAnnualSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:generate-annual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate annual subscriptions for active members based on their starting salary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = Carbon::now()->year;
        $subscriptionName = 'اشتراك عام ' . $currentYear;

        $this->info("Checking annual subscriptions for year: {$currentYear}");

        $memberships = Membership::with(['member.employmentInfo'])
            ->where('status', 'active')
            ->get();

        $generatedCount = 0;

        foreach ($memberships as $membership) {
            $member = $membership->member;

            if (!$member || !$member->employmentInfo) {
                continue;
            }

            // Check if subscription already exists for this year
            $exists = Subscription::where('membership_id', $membership->id)
                ->where('name', $subscriptionName)
                ->exists();

            if (!$exists) {
                $salary = $member->employmentInfo->starting_salary ?? 0;
                
                // Business Rule: The annual fee is calculated as ((Basic Salary * 3%) * 100).
                // Mathematically, this simplifies to just (Salary * 3).
                $annualFee = $salary * 3;

                Subscription::create([
                    'membership_id' => $membership->id,
                    'name'          => $subscriptionName,
                    'amount'        => $annualFee,
                    'due_date'      => Carbon::now()->startOfYear(), // or now(), let's use start of year or current date
                    'status'        => 'unpaid',
                ]);

                $generatedCount++;
            }
        }

        $this->info("Successfully generated {$generatedCount} annual subscriptions.");
    }
}
