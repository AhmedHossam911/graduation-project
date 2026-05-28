<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Auth\OtpCode;
use App\Models\Auth\Notification;
use App\Models\System\Department;
use App\Models\System\AuditLog;
use App\Models\System\SystemSetting;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Attachment;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use App\Models\Services\Claim;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;
use App\Models\Financial\Transaction;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Establish the core roles required for role-based access control (RBAC).
        $adminRole = Role::factory()->create(['name' => 'Admin']);
        $auditorRole = Role::factory()->create(['name' => 'Auditor']);
        $memberRole = Role::factory()->create(['name' => 'Member']);

        // Create the primary Super Admin account (retaining existing known credentials for ease of use).
        $adminUser = User::factory()->create([
            'name' => 'أحمد محمد',
            'email' => 'a511r811r174@gmail.com',
            'role_id' => $adminRole->id,
            'password' => Hash::make('Medo@511'),
            'is_restricted' => false,
            'faculties' => [
                'كلية التمريض',
                'كلية التجارة وإدارة الأعمال',
                'كلية الحاسبات والذكاء الاصطناعي',
                'كلية التكنولوجيا والتعليم',
                'كلية الهندسة بالمطرية',
                'كلية الصيدلة',
                'كلية علوم الرياضة بنين',
                'كلية الطب',
                'المعهد القومي للملكية الفكرية',
                'كلية الهندسة بحلوان',
                'كلية التربية الفنية',
                'كلية الحقوق',
                'كلية الآداب',
                'كلية الفنون الجميلة',
                'كلية الفنون التطبيقية',
                'كلية العلوم',
                'كلية الاقتصاد المنزلي',
                'كلية الخدمة الاجتماعية',
                'كلية التربية',
                'كلية علوم الرياضة بنات',
                'المعهد الفني للتمريض',
                'كلية التربية الموسيقية',
                'كلية علوم التغذية',
                'كلية السياحة و الفنادق'
            ],
            'custom_permissions' => []
        ]);

        // 2. Map out the specific faculties/departments for Helwan University (HU).
        $faculties = [
            'Faculty of Engineering (Mataria)',
            'Faculty of Engineering (Helwan)',
            'Faculty of Commerce',
            'Faculty of Computers and Artificial Intelligence',
            'Faculty of Science',
            'Faculty of Medicine',
            'Faculty of Pharmacy',
            'Faculty of Arts',
            'Faculty of Law',
            'Faculty of Applied Arts'
        ];

        $departments = [];
        foreach($faculties as $fac) {
            $departments[] = Department::factory()->create(['name' => $fac]);
        }
        
        // Create a corresponding member profile for the Admin so they can test features that require a National ID.
        Member::factory()->create([
            'user_id' => $adminUser->id,
            'department_id' => $departments[0]->id,
            'full_name' => 'أحمد محمد',
            'national_id' => '12345678912345'
        ]);

        // Populate the system with some initial configuration settings.
        SystemSetting::factory()->count(10)->create();

        // 3. Bulk Member Generation
        // Efficiently generate exactly 1000 interconnected member profiles.
        // We chunk this process to prevent memory exhaustion during seeding.
        $totalMembers = 1000;
        $chunkSize = 100;
        
        $this->command->info("Seeding 1000 members in logical chunks...");

        for ($i = 0; $i < ($totalMembers / $chunkSize); $i++) {
            
            $users = User::factory()->count($chunkSize)->create(['role_id' => $memberRole->id]);
            
            foreach ($users as $user) {
                // Generate auxiliary data like OTPs and notifications for realism.
                OtpCode::factory()->count(rand(0, 1))->create(['user_id' => $user->id]);
                Notification::factory()->count(rand(1, 3))->create(['user_id' => $user->id]);

                // Construct the foundational member record and link it to a random department.
                $member = Member::factory()->create([
                    'user_id' => $user->id,
                    'department_id' => $departments[array_rand($departments)]->id,
                ]);

                EmploymentInfo::factory()->create(['member_id' => $member->id]);
                FamilyInfo::factory()->create(['member_id' => $member->id]);
                Attachment::factory()->count(rand(1, 2))->create(['member_id' => $member->id]);

                // Initialize the official membership status for the member.
                $membership = Membership::factory()->create([
                    'member_id' => $member->id,
                    'approved_by' => $adminUser->id
                ]);

                // Simulate a history of monthly subscription dues and corresponding payment transactions.
                $subscriptions = Subscription::factory()->count(rand(3, 8))->create(['membership_id' => $membership->id]);
                foreach($subscriptions as $sub) {
                    if ($sub->status === 'paid') {
                        Transaction::factory()->create([
                            'reference_type' => Subscription::class,
                            'reference_id' => $sub->id,
                            'amount' => $sub->amount,
                            'type' => 'IN'
                        ]);
                    }
                }

                // Give roughly 30% of members an active or past loan, complete with installment schedules.
                if (rand(1, 100) <= 30) {
                    $loan = Loan::factory()->create([
                        'membership_id' => $membership->id,
                        'approved_by' => $adminUser->id
                    ]);

                    $installments = Installment::factory()->count($loan->months)->create([
                        'loan_id' => $loan->id,
                        'amount' => $loan->installment_amount
                    ]);

                    foreach($installments as $inst) {
                        if ($inst->status === 'paid') {
                            Transaction::factory()->create([
                                'reference_type' => Installment::class,
                                'reference_id' => $inst->id,
                                'amount' => $inst->amount,
                                'type' => 'IN'
                            ]);
                        }
                    }
                }

                // Give roughly 10% of members an end-of-service or other type of insurance claim.
                if (rand(1, 100) <= 10) {
                    $claim = Claim::factory()->create(['membership_id' => $membership->id]);
                    if($claim->status === 'paid') {
                        Transaction::factory()->create([
                            'reference_type' => Claim::class,
                            'reference_id' => $claim->id,
                            'amount' => $claim->amount,
                            'type' => 'OUT' // Payout claim equals an OUT accounting flag
                        ]);
                    }
                }
            }
        }

        // Finally, generate a trail of random audit logs to simulate system activity.
        AuditLog::factory()->count(150)->create([
            'user_id' => User::inRandomOrder()->first()->id
        ]);

        $this->command->info("Successfully established interconnected tables!");
    }
}
