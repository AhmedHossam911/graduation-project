<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    /**
     * Seed data for the dashboard: users, departments, people, members,
     * memberships, subscriptions, loans, installments, claims, and audit logs.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $today = Carbon::today();

        // ──────────────────────────────────────────────
        // 1. Admin / Employee user (for login)
        // ──────────────────────────────────────────────
        $adminId = DB::table('users')->insertGetId([
            'name'        => 'أحمد محمد',
            'email'       => 'admin@fund.com',
            'national_id' => '12345678912345',
            'password'    => Hash::make('Medo@511'),
            'is_active'   => true,
            'last_login_at' => $now,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $employee2Id = DB::table('users')->insertGetId([
            'name'        => 'هاجر المعتز',
            'email'       => 'hagar@fund.com',
            'national_id' => '29802021234567',
            'password'    => Hash::make('Admin@123'),
            'is_active'   => true,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // ──────────────────────────────────────────────
        // 2. Department & Division
        // ──────────────────────────────────────────────
        $deptId = DB::table('departments')->insertGetId([
            'name'        => 'الشؤون المالية',
            'description' => 'قسم إدارة الشؤون المالية للصندوق',
            'is_active'   => true,
            'created_by'  => $adminId,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $divisionId = DB::table('divisions')->insertGetId([
            'department_id' => $deptId,
            'name'          => 'الاشتراكات والتحصيل',
            'description'   => 'إدارة اشتراكات الأعضاء وتحصيل المستحقات',
            'is_active'     => true,
            'created_by'    => $adminId,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // ──────────────────────────────────────────────
        // 3. People & Members (12 members, 9 active)
        // ──────────────────────────────────────────────
        $peopleData = [
            ['first_name' => 'محمد',   'second_name' => 'أحمد',   'third_name' => 'علي',    'fourth_name' => 'حسن',    'national_id' => '28501011234567', 'gender' => 'male',   'phone' => '01012345678'],
            ['first_name' => 'فاطمة',  'second_name' => 'محمود',  'third_name' => 'إبراهيم','fourth_name' => 'سالم',   'national_id' => '29001021234567', 'gender' => 'female', 'phone' => '01112345678'],
            ['first_name' => 'علي',    'second_name' => 'حسين',   'third_name' => 'محمد',   'fourth_name' => 'عبدالله','national_id' => '28801031234567', 'gender' => 'male',   'phone' => '01212345678'],
            ['first_name' => 'نورا',   'second_name' => 'خالد',   'third_name' => 'سعيد',   'fourth_name' => 'عمر',    'national_id' => '29201041234567', 'gender' => 'female', 'phone' => '01512345678'],
            ['first_name' => 'يوسف',  'second_name' => 'عادل',   'third_name' => 'رضا',    'fourth_name' => 'حامد',   'national_id' => '28701051234567', 'gender' => 'male',   'phone' => '01012345679'],
            ['first_name' => 'سارة',   'second_name' => 'ماجد',   'third_name' => 'فؤاد',   'fourth_name' => 'ناصر',   'national_id' => '29101061234567', 'gender' => 'female', 'phone' => '01112345679'],
            ['first_name' => 'عمر',    'second_name' => 'طارق',   'third_name' => 'مصطفى',  'fourth_name' => 'كمال',   'national_id' => '28601071234567', 'gender' => 'male',   'phone' => '01212345679'],
            ['first_name' => 'مريم',   'second_name' => 'هشام',   'third_name' => 'جمال',   'fourth_name' => 'صالح',   'national_id' => '29301081234567', 'gender' => 'female', 'phone' => '01512345679'],
            ['first_name' => 'حسن',    'second_name' => 'وليد',   'third_name' => 'سمير',   'fourth_name' => 'بكر',    'national_id' => '28901091234567', 'gender' => 'male',   'phone' => '01012345680'],
            ['first_name' => 'آية',    'second_name' => 'شريف',   'third_name' => 'عاطف',   'fourth_name' => 'رشدي',   'national_id' => '29401101234567', 'gender' => 'female', 'phone' => '01112345680'],
            ['first_name' => 'كريم',   'second_name' => 'نبيل',   'third_name' => 'فتحي',   'fourth_name' => 'حمدي',   'national_id' => '28801111234567', 'gender' => 'male',   'phone' => '01212345680'],
            ['first_name' => 'رنا',    'second_name' => 'ياسر',   'third_name' => 'أنور',   'fourth_name' => 'رجب',    'national_id' => '29501121234567', 'gender' => 'female', 'phone' => '01512345680'],
        ];

        $memberIds = [];
        $memberStatuses = ['active','active','active','active','active','active','active','active','active','suspended','terminated','suspended'];

        foreach ($peopleData as $i => $p) {
            $personId = DB::table('people')->insertGetId(array_merge($p, [
                'date_of_birth'  => '19' . substr($p['national_id'], 1, 2) . '-' . substr($p['national_id'], 3, 2) . '-' . substr($p['national_id'], 5, 2),
                'nationality'    => 'Egyptian',
                'marital_status' => $i % 2 === 0 ? 'married' : 'single',
                'created_by'     => $adminId,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]));

            $memberId = DB::table('members')->insertGetId([
                'person_id'     => $personId,
                'member_number' => 'MEM-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'status'        => $memberStatuses[$i],
                'join_date'     => $today->copy()->subMonths(rand(6, 24))->toDateString(),
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            $memberIds[] = $memberId;

            // Link member to division
            DB::table('member_divisions')->insert([
                'member_id'   => $memberId,
                'division_id' => $divisionId,
            ]);
        }

        // ──────────────────────────────────────────────
        // 4. Memberships (for active members)
        // ──────────────────────────────────────────────
        $activeMemberIds = array_slice($memberIds, 0, 9);
        $membershipIds = [];

        foreach ($activeMemberIds as $mId) {
            $membershipId = DB::table('memberships')->insertGetId([
                'member_id'           => $mId,
                'start_date'          => $today->copy()->subMonths(12)->toDateString(),
                'end_date'            => null,
                'subscription_amount' => 150.00,
                'status'              => 'active',
                'created_by'          => $adminId,
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);
            $membershipIds[$mId] = $membershipId;
        }

        // ──────────────────────────────────────────────
        // 5. Subscriptions – some created today for the dashboard card
        // ──────────────────────────────────────────────
        // 4 subscriptions created today
        for ($i = 0; $i < 4; $i++) {
            $mId = $activeMemberIds[$i];
            DB::table('subscriptions')->insert([
                'member_id'     => $mId,
                'membership_id' => $membershipIds[$mId],
                'amount'        => 150.00,
                'frequency'     => 'monthly',
                'start_date'    => $today->toDateString(),
                'next_due_date' => $today->copy()->addMonth()->toDateString(),
                'is_active'     => true,
                'created_by'    => $adminId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // 3 older subscriptions
        for ($i = 4; $i < 7; $i++) {
            $mId = $activeMemberIds[$i];
            DB::table('subscriptions')->insert([
                'member_id'     => $mId,
                'membership_id' => $membershipIds[$mId],
                'amount'        => 150.00,
                'frequency'     => 'monthly',
                'start_date'    => $today->copy()->subMonth()->toDateString(),
                'next_due_date' => $today->toDateString(),
                'is_active'     => true,
                'created_by'    => $adminId,
                'created_at'    => $today->copy()->subMonth(),
                'updated_at'    => $today->copy()->subMonth(),
            ]);
        }

        // ──────────────────────────────────────────────
        // 6. Loans & Installments – some due today
        // ──────────────────────────────────────────────
        $loanMembers = [$activeMemberIds[0], $activeMemberIds[2], $activeMemberIds[4]];

        foreach ($loanMembers as $idx => $mId) {
            $loanId = DB::table('loans')->insertGetId([
                'member_id'           => $mId,
                'principal_amount'    => 10000.00,
                'interest_rate'       => 5.00,
                'duration_months'     => 12,
                'monthly_installment' => 875.00,
                'status'              => 'active',
                'purpose'             => 'قرض شخصي',
                'approved_by'         => $adminId,
                'approval_date'       => $today->copy()->subMonths(3)->toDateString(),
                'start_date'          => $today->copy()->subMonths(3)->toDateString(),
                'end_date'            => $today->copy()->addMonths(9)->toDateString(),
                'remaining_balance'   => 10000 - (875 * 2),
                'created_by'          => $adminId,
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);

            // Past paid installments
            for ($inst = 1; $inst <= 2; $inst++) {
                DB::table('installments')->insert([
                    'loan_id'            => $loanId,
                    'installment_number' => $inst,
                    'due_date'           => $today->copy()->subMonths(3 - $inst)->toDateString(),
                    'amount'             => 875.00,
                    'status'             => 'paid',
                    'paid_at'            => $today->copy()->subMonths(3 - $inst),
                    'created_by'         => $adminId,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);
            }

            // Installment due today (pending)
            DB::table('installments')->insert([
                'loan_id'            => $loanId,
                'installment_number' => 3,
                'due_date'           => $today->toDateString(),
                'amount'             => 875.00,
                'status'             => 'pending',
                'paid_at'            => null,
                'created_by'         => $adminId,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // ──────────────────────────────────────────────
        // 7. Claims – mix of pending and others
        // ──────────────────────────────────────────────
        $claimData = [
            ['member_id' => $activeMemberIds[1], 'type' => 'marriage',    'requested_amount' => 5000, 'status' => 'pending',  'approved_amount' => null],
            ['member_id' => $activeMemberIds[3], 'type' => 'retirement',  'requested_amount' => 15000,'status' => 'pending',  'approved_amount' => null],
            ['member_id' => $activeMemberIds[5], 'type' => 'disability',  'requested_amount' => 8000, 'status' => 'pending',  'approved_amount' => null],
            ['member_id' => $activeMemberIds[6], 'type' => 'other',       'requested_amount' => 3000, 'status' => 'pending',  'approved_amount' => null],
            ['member_id' => $activeMemberIds[7], 'type' => 'death',       'requested_amount' => 20000,'status' => 'pending',  'approved_amount' => null],
            ['member_id' => $activeMemberIds[0], 'type' => 'marriage',    'requested_amount' => 5000, 'status' => 'approved', 'approved_amount' => 5000],
            ['member_id' => $activeMemberIds[2], 'type' => 'retirement',  'requested_amount' => 12000,'status' => 'delivered','approved_amount' => 12000],
        ];

        foreach ($claimData as $claim) {
            DB::table('claims')->insert(array_merge($claim, [
                'request_date' => $today->copy()->subDays(rand(1, 30))->toDateString(),
                'approved_by'  => $claim['status'] !== 'pending' ? $adminId : null,
                'approval_date'=> $claim['status'] !== 'pending' ? $today->copy()->subDays(rand(1, 5))->toDateString() : null,
                'created_by'   => $employee2Id,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]));
        }

        // ──────────────────────────────────────────────
        // 8. Audit Logs (today's operations for the table)
        // ──────────────────────────────────────────────
        $auditData = [
            ['user_id' => $adminId,     'action' => 'login',  'entity_type' => 'user',         'entity_id' => $adminId,            'minutes_ago' => 120],
            ['user_id' => $employee2Id, 'action' => 'login',  'entity_type' => 'user',         'entity_id' => $employee2Id,        'minutes_ago' => 100],
            ['user_id' => $adminId,     'action' => 'create', 'entity_type' => 'member',       'entity_id' => $memberIds[0],       'minutes_ago' => 90],
            ['user_id' => $adminId,     'action' => 'create', 'entity_type' => 'member',       'entity_id' => $memberIds[1],       'minutes_ago' => 85],
            ['user_id' => $employee2Id, 'action' => 'create', 'entity_type' => 'subscription', 'entity_id' => 1,                   'minutes_ago' => 75],
            ['user_id' => $employee2Id, 'action' => 'create', 'entity_type' => 'subscription', 'entity_id' => 2,                   'minutes_ago' => 70],
            ['user_id' => $adminId,     'action' => 'update', 'entity_type' => 'member',       'entity_id' => $memberIds[2],       'minutes_ago' => 60],
            ['user_id' => $adminId,     'action' => 'create', 'entity_type' => 'loan',         'entity_id' => 1,                   'minutes_ago' => 50],
            ['user_id' => $employee2Id, 'action' => 'create', 'entity_type' => 'claim',        'entity_id' => 1,                   'minutes_ago' => 40],
            ['user_id' => $employee2Id, 'action' => 'create', 'entity_type' => 'payment',      'entity_id' => 1,                   'minutes_ago' => 30],
            ['user_id' => $adminId,     'action' => 'update', 'entity_type' => 'installment',  'entity_id' => 1,                   'minutes_ago' => 20],
            ['user_id' => $adminId,     'action' => 'create', 'entity_type' => 'claim',        'entity_id' => 2,                   'minutes_ago' => 10],
        ];

        foreach ($auditData as $log) {
            DB::table('audit_logs')->insert([
                'user_id'     => $log['user_id'],
                'action'      => $log['action'],
                'entity_type' => $log['entity_type'],
                'entity_id'   => $log['entity_id'],
                'ip_address'  => '192.168.1.' . rand(1, 254),
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at'  => $now->copy()->subMinutes($log['minutes_ago']),
                'updated_at'  => $now->copy()->subMinutes($log['minutes_ago']),
            ]);
        }
    }
}
