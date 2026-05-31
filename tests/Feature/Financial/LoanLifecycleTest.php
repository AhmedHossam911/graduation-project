<?php

namespace Tests\Feature\Financial;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Membership\Member;
use App\Models\Membership\EmploymentInfo;
use App\Models\Services\Membership;
use App\Models\Financial\Loan;
use App\Models\System\SystemSetting;

class LoanLifecycleTest extends TestCase
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
            'custom_permissions' => ['إدارة القروض', 'إدارة الأعضاء']
        ]);
        
        SystemSetting::updateOrCreate(['key' => 'loan_max_amount'], ['value' => '20000']);
        SystemSetting::updateOrCreate(['key' => 'loan_repayment_months'], ['value' => '36']);
        SystemSetting::updateOrCreate(['key' => 'loan_min_years_subscribed'], ['value' => '0']);
    }

    public function test_employee_can_view_loans_index()
    {
        $response = $this->actingAs($this->employee)->get(route('loans.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employee.loans.index');
    }

    public function test_employee_can_validate_loan_request()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id, 'status' => 'active']);
        
        EmploymentInfo::factory()->create([
            'member_id' => $member->id,
            'retirement_date' => now()->addYears(10)->format('Y-m-d')
        ]);
        
        // Add paid subscriptions to meet total amount rule
        \App\Models\Services\Subscription::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'paid',
            'amount' => 15000
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.validateRequest'), [
            'member_id' => $member->id,
            'total_amount' => 10000,
            'months' => 12
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_employee_can_store_loan_request()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id, 'status' => 'active']);
        
        EmploymentInfo::factory()->create([
            'member_id' => $member->id,
            'retirement_date' => now()->addYears(10)->format('Y-m-d')
        ]);
        
        \App\Models\Services\Subscription::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'paid',
            'amount' => 15000
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.store'), [
            'member_id' => $member->id,
            'total_amount' => 10000,
            'months' => 12,
            'declaration_file' => UploadedFile::fake()->create('declaration.pdf', 100),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loans', [
            'membership_id' => $membership->id,
            'status' => 'pending',
            'base_amount' => 10000,
            'months' => 12
        ]);
    }

    public function test_employee_can_approve_loan()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.approve', $loan->id));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'active',
            'approved_by' => $this->employee->id
        ]);
    }

    public function test_employee_can_start_loan_and_generate_installments()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'active',
            'months' => 12,
            'installment_amount' => 1000
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.start', $loan->id), [
            'check_image' => UploadedFile::fake()->create('check.pdf', 100),
            'board_approval_image' => UploadedFile::fake()->create('board.pdf', 100),
        ]);

        $response->assertSessionHas('success');

        // Check if 12 installments were generated
        $this->assertDatabaseCount('installments', 12);
        
        $this->assertDatabaseHas('transactions', [
            'reference_type' => Loan::class,
            'reference_id' => $loan->id,
            'type' => 'OUT',
            'category' => 'loan_start'
        ]);
    }

    public function test_employee_can_cancel_loan()
    {
        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.cancel', $loan->id), [
            'reason' => 'Not eligible',
            'details' => 'Missing paperwork'
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'rejected',
        ]);
    }
}
