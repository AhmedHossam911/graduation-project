<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\System\SystemSetting;
use App\Models\System\Department;
use App\Models\Membership\Member;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Establish the core roles required for role-based access control (RBAC).
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'employee']);
        Role::firstOrCreate(['name' => 'member']);

        // Create the primary Super Admin account (retaining existing known credentials for ease of use).
        $adminUser = User::firstOrCreate(
            ['email' => 'a511r811r174@gmail.com'],
            [
                'name' => 'احمد حسام',
                'national_id' => '12345678912345',
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
            ]
        );

        // Map out the specific faculties/departments for Helwan University (HU).
        $faculties = [
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
        ];

        $departments = [];
        foreach($faculties as $fac) {
            $departments[] = Department::firstOrCreate(['name' => $fac]);
        }
        
        // Create a corresponding member profile for the Admin so they can test features that require a National ID.
        Member::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'department_id' => $departments[0]->id
            ]
        );

        // 2. Seed System Settings
        foreach (SystemSetting::$defaults as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info("Seeded Admin, Member Profile, Roles, Departments and System Settings successfully!");

        $this->call(BusinessRulesSeeder::class);
    }
}
