<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\System\Department;
use App\Models\System\SystemSetting;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Services\Subscription;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Services\Claim;
use Carbon\Carbon;
use Faker\Factory as Faker;

class BusinessRulesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        $memberRole = Role::where('name', 'member')->first();
        $departments = Department::all();

        if (!$memberRole || $departments->isEmpty()) {
            $this->command->error("Please run DatabaseSeeder first to ensure Roles and Departments exist.");
            return;
        }
        
        $loanInterestRate = (float) SystemSetting::get('loan_interest_rate', 8);
        $loanMaxAmount = (float) SystemSetting::get('loan_max_amount', 20000);
        $retirementAge = (int) SystemSetting::get('retirement_age', 60);

        $this->command->info("Seeding Members according to Business Rules...");

        // 1. New Users applying for membership (Pending Registration)
        for ($i = 1; $i <= 10; $i++) {
            $this->createMemberScenario($faker, $memberRole, $departments->random(), 'pending_registration', false, false, $retirementAge);
        }

        // 2. Old Active Members (with past subscriptions)
        for ($i = 1; $i <= 20; $i++) {
            $this->createMemberScenario($faker, $memberRole, $departments->random(), 'active', false, false, $retirementAge);
        }

        // 3. Active Members with Loans
        for ($i = 1; $i <= 10; $i++) {
            $this->createMemberScenario($faker, $memberRole, $departments->random(), 'active', true, false, $retirementAge, $loanMaxAmount, $loanInterestRate);
        }

        // 4. Members with Claims (Retired, Resigned, Death, etc.)
        for ($i = 1; $i <= 10; $i++) {
            $this->createMemberScenario($faker, $memberRole, $departments->random(), 'pension_eligible', false, true, $retirementAge);
        }

        $this->command->info("Business Rules Seeder completed successfully!");
    }

    private function createMemberScenario($faker, $memberRole, $department, $status, $hasLoan, $hasClaim, $retirementAge, $loanMaxAmount = 20000, $loanInterestRate = 8)
    {
        // Generate valid Egyptian National ID
        if ($status === 'pension_eligible') {
            $birthYear = date('Y') - $retirementAge - $faker->numberBetween(0, 5); // Past retirement age
        } else {
            $birthYear = $faker->numberBetween(date('Y') - $retirementAge + 1, date('Y') - 22); // Between 22 and 59
        }
        
        $centuryCode = $birthYear < 2000 ? '2' : '3';
        $yearCode = substr((string)$birthYear, -2);
        $monthCode = str_pad($faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $dayCode = str_pad($faker->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);
        $nationalId = $centuryCode . $yearCode . $monthCode . $dayCode . $faker->unique()->numerify('######');

        $user = User::create([
            'name' => $faker->name,
            'national_id' => $nationalId,
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make($nationalId),
            'role_id' => $memberRole->id,
        ]);

        $birthDate = Carbon::createFromDate($birthYear, (int)$monthCode, (int)$dayCode);

        $member = Member::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'birth_date' => $birthDate->format('Y-m-d'),
            'phone' => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
            'landline' => '02' . $faker->numerify('#######'),
            'address' => $faker->address,
            'marital_status' => $faker->randomElement(['متزوج', 'مطلق', 'أعزب', 'أرمل']),
        ]);

        $joinDate = $birthDate->copy()->addYears(22)->addDays($faker->numberBetween(1, 1000));
        if ($joinDate->isFuture()) {
            $joinDate = Carbon::now()->subMonths($faker->numberBetween(1, 12));
        }

        $retirementDate = $birthDate->copy()->addYears($retirementAge);
        $startingSalary = $faker->numberBetween(1000, 5000);

        EmploymentInfo::create([
            'member_id' => $member->id,
            'workplace' => $department->name,
            'job_title' => $faker->jobTitle,
            'financial_category' => 'الفئة ' . $faker->randomElement(['الأولى', 'الثانية', 'الثالثة']),
            'join_date' => $joinDate->format('Y-m-d'),
            'retirement_date' => $retirementDate->format('Y-m-d'),
            'starting_salary' => $startingSalary,
        ]);

        FamilyInfo::create([
            'member_id' => $member->id,
            'children_count' => $faker->numberBetween(0, 4),
            'spouse_name' => $faker->name,
            'spouse_phone' => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
            'spouse_workplace' => $faker->company,
            'child_name' => $faker->name,
            'child_workplace' => 'طالب',
        ]);

        $membership = Membership::create([
            'member_id' => $member->id,
            'membership_number' => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
            'status' => $status,
            'declaration_accepted' => true,
            'approved_by' => ($status === 'active' || $status === 'pension_eligible') ? 1 : null,
        ]);

        // Join Fee Subscription
        $ageAtJoin = $joinDate->diffInYears($birthDate);
        $remainingYears = max(0, $retirementAge - $ageAtJoin);
        $feesSettings = json_decode(SystemSetting::get('membership_join_fee', '[]'), true);
        $feeMonths = 0;
        if (is_array($feesSettings)) {
            $maxSettingYear = 0;
            $settingsData = [];
            foreach ($feesSettings as $setting) {
                $settingYearsStr = preg_replace('/[^0-9]/', '', $setting['years'] ?? '');
                if (is_numeric($settingYearsStr)) {
                    $sy = (int) $settingYearsStr;
                    $settingsData[$sy] = (float) ($setting['fee_months'] ?? 0);
                    if ($sy > $maxSettingYear) $maxSettingYear = $sy;
                }
            }
            if ($remainingYears > $maxSettingYear && $maxSettingYear > 0) {
                $remainingYears = $maxSettingYear;
            }
            if (isset($settingsData[$remainingYears])) {
                $feeMonths = $settingsData[$remainingYears];
            }
        }

        $joinFeeAmount = $startingSalary * $feeMonths;
        if ($joinFeeAmount <= 0) {
            $joinFeeAmount = 500;
        }

        Subscription::create([
            'membership_id' => $membership->id,
            'name' => 'رسم انضمام',
            'amount' => $joinFeeAmount,
            'due_date' => $joinDate->format('Y-m-d'),
            'status' => ($status === 'active' || $status === 'pension_eligible') ? 'paid' : 'unpaid',
        ]);

        // Monthly Subscriptions if active or pension_eligible
        if ($status === 'active' || $status === 'pension_eligible') {
            $monthsSubscribed = min($joinDate->diffInMonths(Carbon::now()), 24); // max 2 years for seeding dummy to save time
            for ($m = 1; $m <= $monthsSubscribed; $m++) {
                $subDate = clone $joinDate;
                $subDate->addMonths($m);
                
                // If it's in the future, break
                if ($subDate->isFuture()) break;

                Subscription::create([
                    'membership_id' => $membership->id,
                    'name' => 'اشتراك شهر ' . $subDate->format('m-Y'),
                    'amount' => $startingSalary * ((float) SystemSetting::get('subscription_percentage', 10) / 100),
                    'due_date' => $subDate->format('Y-m-d'),
                    'status' => 'paid',
                ]);
            }
        }

        // Loans
        if ($hasLoan && $status === 'active') {
            $duration = $faker->randomElement([12, 24, 36]);
            $loanAmount = $faker->numberBetween(5000, $loanMaxAmount);
            
            $interestAmount = $loanAmount * ($loanInterestRate / 100) * ($duration / 12);
            $totalAmount = $loanAmount + $interestAmount;
            $installmentAmount = $totalAmount / $duration;

            $loan = Loan::create([
                'membership_id' => $membership->id,
                'base_amount' => $loanAmount,
                'interest_amount' => $interestAmount,
                'total_amount' => $totalAmount,
                'installment_amount' => $installmentAmount,
                'months' => $duration,
                'status' => 'active',
                'approved_by' => 1
            ]);

            $startDate = Carbon::now()->subMonths($faker->numberBetween(1, $duration - 1));
            for ($m = 1; $m <= $duration; $m++) {
                $dueDate = clone $startDate;
                $dueDate->addMonths($m);
                
                $instStatus = 'unpaid';
                if ($dueDate->isPast()) {
                    $instStatus = 'paid';
                }

                Installment::create([
                    'loan_id' => $loan->id,
                    'amount' => $installmentAmount,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'status' => $instStatus,
                ]);
            }
        }

        // Claims
        if ($hasClaim) {
            $claimType = $faker->randomElement(array_keys(Claim::CLAIM_TYPES));
            
            $basicClaimPercentage = (float) SystemSetting::get('claim_basic_percentage', 145);
            $yearsSubscribed = $joinDate->diffInYears(Carbon::now());
            if ($yearsSubscribed < 1) $yearsSubscribed = 1; // minimum 1 year multiplier for fallback
            $claimAmount = $startingSalary * ($basicClaimPercentage / 100) * $yearsSubscribed;
            if ($claimAmount <= 0) $claimAmount = 10000;

            Claim::create([
                'membership_id' => $membership->id,
                'type' => $claimType,
                'amount' => $claimAmount,
                'status' => $faker->randomElement(['pending', 'approved', 'paid']),
                'attachment_receipt' => null,
            ]);
        }
    }
}
