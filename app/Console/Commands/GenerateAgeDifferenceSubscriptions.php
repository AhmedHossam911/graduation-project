<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use App\Models\System\SystemSetting;
use Carbon\Carbon;

class GenerateAgeDifferenceSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:generate-age-difference';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing subscriptions up to the retirement age (اشتراك فرق السن)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentYear = Carbon::now()->year;
        $retirementAge = (int) SystemSetting::get('retirement_age', 60);

        $this->info("Checking missing age difference subscriptions for year: {$currentYear} up to retirement age: {$retirementAge}");

        $memberships = Membership::with(['member.employmentInfo'])
            ->whereIn('status', ['active', 'loaned', 'unpaid_leave'])
            ->get();

        $generatedCount = 0;

        foreach ($memberships as $membership) {
            $member = $membership->member;

            if (!$member || !$member->employmentInfo || !$member->birth_date) {
                continue;
            }

            // Calculate the retirement year based on birth date and the current system retirement age.
            $retirementYear = Carbon::parse($member->birth_date)->addYears($retirementAge)->year;
            $salary = $member->employmentInfo->starting_salary ?? 0;
            $amount = $salary * 3;

            if ($amount <= 0 || $retirementYear < $currentYear) {
                continue;
            }

            // Fetch all existing subscriptions to avoid querying inside the loop
            $existingSubscriptions = Subscription::where('membership_id', $membership->id)
                ->pluck('name')
                ->toArray();

            $newSubscriptions = [];

            // Ensure the member has subscriptions for every year from current year up to their retirement year
            for ($year = $currentYear; $year <= $retirementYear; $year++) {
                $annualName = 'اشتراك عام ' . $year;
                $diffName   = 'اشتراك فرق السن لعام ' . $year;

                if (!in_array($annualName, $existingSubscriptions) && !in_array($diffName, $existingSubscriptions)) {
                    $newSubscriptions[] = [
                        'membership_id' => $membership->id,
                        'name'          => $diffName,
                        'amount'        => $amount,
                        'due_date'      => Carbon::create($year, 7, 1)->startOfDay(),
                        'status'        => 'unpaid',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }

            if (!empty($newSubscriptions)) {
                Subscription::insert($newSubscriptions);
                $generatedCount += count($newSubscriptions);
            }
        }

        $this->info("Successfully generated {$generatedCount} missing age difference subscriptions.");
    }
}
