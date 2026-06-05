<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\System\Department;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Services\Subscription;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        $memberRole = Role::where('name', 'Member')->first();
        $departments = Department::all();

        if (!$memberRole || $departments->isEmpty()) {
            $this->command->error("Please run DatabaseSeeder first to ensure Roles and Departments exist.");
            return;
        }

        $statuses = ['active', 'pending_registration', 'suspended', 'withdrawn', 'loaned', 'membership_expired'];
        $maritalStatuses = ['متزوج', 'مطلق', 'أعزب', 'أرمل'];

        $this->command->info("Seeding 50 dummy members...");

        for ($i = 1; $i <= 50; $i++) {
            // 1. User
            $nationalId = $faker->unique()->numerify('##############'); // 14 digits
            $user = User::create([
                'name' => $faker->name,
                'national_id' => $nationalId,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make($nationalId),
                'role_id' => $memberRole->id,
            ]);

            // 2. Member
            $birthYear = $faker->numberBetween(1965, 2002);
            $birthDate = Carbon::create($birthYear, $faker->month, $faker->dayOfMonth);
            $department = $departments->random();

            $member = Member::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'birth_date' => $birthDate->format('Y-m-d'),
                'phone' => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
                'landline' => '02' . $faker->numerify('#######'),
                'address' => $faker->address,
                'marital_status' => $faker->randomElement($maritalStatuses),
            ]);

            // 3. Employment Info
            $joinDate = $birthDate->copy()->addYears(22)->addDays($faker->numberBetween(1, 1000));
            $retirementDate = $birthDate->copy()->addYears(60);
            
            EmploymentInfo::create([
                'member_id' => $member->id,
                'workplace' => $department->name,
                'job_title' => $faker->jobTitle,
                'financial_category' => 'الفئة ' . $faker->randomElement(['الأولى', 'الثانية', 'الثالثة']),
                'join_date' => $joinDate->format('Y-m-d'),
                'retirement_date' => $retirementDate->format('Y-m-d'),
                'starting_salary' => $faker->numberBetween(300, 1500),
            ]);

            // 4. Family Info
            FamilyInfo::create([
                'member_id' => $member->id,
                'children_count' => $faker->numberBetween(0, 4),
                'spouse_name' => $faker->name,
                'spouse_phone' => '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->numerify('########'),
                'spouse_workplace' => $faker->company,
                'child_name' => $faker->name,
                'child_workplace' => 'طالب',
            ]);

            // 5. Membership
            $status = $faker->randomElement($statuses);
            // Bias towards active
            if ($faker->boolean(70)) {
                $status = 'active';
            }

            $membership = Membership::create([
                'member_id' => $member->id,
                'membership_number' => 'MS-' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'status' => $status,
                'declaration_accepted' => true,
                'approved_by' => $status === 'active' ? 1 : null,
            ]);

            // Calculate realistic Joining Fee (رسم انضمام)
            $retirementAge = 60; // Usually 60
            $ageAtJoin = $joinDate->diffInYears($birthDate);
            $remainingYears = max(0, $retirementAge - $ageAtJoin);
            $feesSettings = json_decode(\App\Models\System\SystemSetting::get('membership_join_fee', '[]'), true);
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
            
            // Assume salary (must match what was saved in EmploymentInfo)
            $salary = \App\Models\Membership\EmploymentInfo::where('member_id', $member->id)->value('starting_salary') ?? $faker->numberBetween(300, 1500);
            $joinFeeAmount = $salary * $feeMonths;
            if ($joinFeeAmount <= 0) {
                $joinFeeAmount = 500; // Fallback
            }

            // Always create the Join Fee subscription
            Subscription::create([
                'membership_id' => $membership->id,
                'name' => 'رسم انضمام',
                'amount' => $joinFeeAmount,
                'due_date' => $joinDate->format('Y-m-d'),
                'status' => $status === 'active' ? ($faker->boolean(90) ? 'paid' : 'unpaid') : 'unpaid',
            ]);

            // 6. Other Subscriptions (Simulate 1 to 2 subscriptions)
            $subCount = $faker->numberBetween(0, 2);
            for ($j = 0; $j < $subCount; $j++) {
                $subStatus = $faker->randomElement(['paid', 'unpaid', 'overdue']);
                if ($status === 'active') {
                    $subStatus = $faker->boolean(80) ? 'paid' : 'unpaid';
                }

                Subscription::create([
                    'membership_id' => $membership->id,
                    'name' => 'اشتراك ' . $faker->randomElement(['شهري', 'سنوي']),
                    'amount' => $faker->randomElement([150, 300, 500, 1000]),
                    'due_date' => Carbon::now()->subMonths($faker->numberBetween(0, 12))->format('Y-m-d'),
                    'status' => $subStatus,
                ]);
            }

            // 7. Loans (Only for some active members)
            if ($status === 'active' && $faker->boolean(30)) {
                $duration = $faker->randomElement([12, 24, 36]);
                $loanAmount = $faker->numberBetween(5000, 20000);
                $totalAmount = $loanAmount * 1.1; // 10% interest for dummy
                $installmentAmount = $totalAmount / $duration;

                $loan = Loan::create([
                    'membership_id' => $membership->id,
                    'base_amount' => $loanAmount,
                    'total_amount' => $totalAmount,
                    'installment_amount' => $installmentAmount,
                    'months' => $duration,
                    'status' => 'active',
                ]);

                // Create some installments
                $startDate = Carbon::now()->subMonths($faker->numberBetween(1, 5));
                for ($m = 1; $m <= $duration; $m++) {
                    $dueDate = clone $startDate;
                    $dueDate->addMonths($m);
                    
                    $instStatus = 'unpaid';
                    if ($dueDate->isPast()) {
                        $instStatus = $faker->boolean(90) ? 'paid' : 'overdue';
                    }

                    Installment::create([
                        'loan_id' => $loan->id,
                        'amount' => $installmentAmount,
                        'due_date' => $dueDate->format('Y-m-d'),
                        'status' => $instStatus,
                    ]);
                }
            }
        }

        $this->command->info("50 Dummy members created successfully!");
    }
}
