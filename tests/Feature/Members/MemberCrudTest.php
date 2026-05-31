<?php

namespace Tests\Feature\Members;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\Department;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\FamilyInfo;

class MemberCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::factory()->create(['name' => 'employee']);
        $this->employee = User::factory()->create([
            'role_id' => $role->id,
            'is_restricted' => false,
            'custom_permissions' => ['إدارة الأعضاء']
        ]);

        Department::factory()->create(['name' => 'IT Department', 'status' => 'active']);
    }

    public function test_employee_can_view_members_index()
    {
        $response = $this->actingAs($this->employee)->get(route('members.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employee.members.index');
    }

    public function test_employee_can_create_member_with_valid_data()
    {
        Storage::fake('public');

        $department = Department::first();

        $data = [
            'full_name' => 'Test Member',
            'email' => 'testmember@example.com',
            'department_id' => $department->id,
            'national_id_digits' => str_split('12345678901234'),
            'birth_day' => 1,
            'birth_month' => 1,
            'birth_year' => 1990,
            'marital_status' => 'أعزب',
            'employer_name' => 'Test Corp',
            'job_title' => 'Engineer',
            'financial_category' => 'A',
            'hire_day' => 1,
            'hire_month' => 1,
            'hire_year' => 2015,
            'retirement_day' => 1,
            'retirement_month' => 1,
            'retirement_year' => 2050,
            'salary' => 5000,
            'documents' => [
                'national_id_card' => UploadedFile::fake()->create('id.pdf', 100),
                'basic_salary_letter' => UploadedFile::fake()->create('salary.pdf', 100),
                'work_declaration' => UploadedFile::fake()->create('work.pdf', 100),
                'over_21_request' => UploadedFile::fake()->create('request.pdf', 100),
                'appointment_decision' => UploadedFile::fake()->create('appointment.pdf', 100),
                'manual_request' => UploadedFile::fake()->create('manual.pdf', 100),
            ]
        ];

        $response = $this->actingAs($this->employee)->post(route('members.store'), $data);

        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'national_id' => '12345678901234',
            'email' => 'testmember@example.com'
        ]);

        $user = User::where('national_id', '12345678901234')->first();
        
        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'department_id' => $department->id
        ]);
    }

    public function test_employee_can_update_member()
    {
        $member = Member::factory()->create();
        $department = Department::first();

        $data = [
            'full_name' => 'Updated Member Name',
            'email' => 'updated@example.com',
            'department_id' => $department->id,
            'national_id_digits' => str_split($member->user->national_id), // same national ID
            'birth_day' => 2,
            'birth_month' => 2,
            'birth_year' => 1992,
            'marital_status' => 'متزوج',
            'employer_name' => 'Updated Corp',
            'job_title' => 'Senior Engineer',
            'financial_category' => 'B',
            'hire_day' => 2,
            'hire_month' => 2,
            'hire_year' => 2016,
            'retirement_day' => 2,
            'retirement_month' => 2,
            'retirement_year' => 2052,
            'salary' => 8000,
        ];

        $response = $this->actingAs($this->employee)->put(route('members.update', $member->id), $data);

        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $member->user_id,
            'name' => 'Updated Member Name'
        ]);

        $this->assertDatabaseHas('employment_info', [
            'member_id' => $member->id,
            'workplace' => 'Updated Corp'
        ]);
    }

    public function test_employee_can_suspend_member()
    {
        Storage::fake('public');
        $member = Member::factory()->create();
        
        $response = $this->actingAs($this->employee)->post(route('members.suspend', $member->id), [
            'reason' => 'Suspension Reason',
            'suspension_file' => UploadedFile::fake()->create('suspension.pdf', 100),
        ]);

        $response->assertSessionHas('success');
        
        // Assert that a suspension logic has been run (might require checking MembershipInfo status if exists)
        // Here we just test the endpoint completes successfully
    }

    public function test_employee_can_soft_delete_member()
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->employee)->delete(route('members.destroy', $member->id));

        $response->assertRedirect(route('members.index'));
        $this->assertSoftDeleted('members', ['id' => $member->id]);
    }

    public function test_create_member_validation_fails_on_duplicate_national_id()
    {
        $existingUser = User::factory()->create(['national_id' => '12345678901234']);

        $department = Department::first();

        $data = [
            'full_name' => 'Test Member',
            'email' => 'newemail@example.com',
            'department_id' => $department->id,
            'national_id_digits' => str_split('12345678901234'), // duplicate
            'birth_day' => 1,
            'birth_month' => 1,
            'birth_year' => 1990,
            'marital_status' => 'أعزب',
            'employer_name' => 'Test Corp',
            'job_title' => 'Engineer',
            'financial_category' => 'A',
            'hire_day' => 1,
            'hire_month' => 1,
            'hire_year' => 2015,
            'retirement_day' => 1,
            'retirement_month' => 1,
            'retirement_year' => 2050,
            'salary' => 5000,
        ];

        $response = $this->actingAs($this->employee)->post(route('members.store'), $data);

        $response->assertSessionHasErrors('national_id_digits');
    }
}
