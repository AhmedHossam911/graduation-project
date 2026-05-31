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
use App\Models\Services\Membership;
use App\Models\Financial\Loan;
use App\Models\Financial\Installment;

class InstallmentPaymentTest extends TestCase
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
            'custom_permissions' => ['إدارة القروض']
        ]);
    }

    public function test_employee_can_pay_multiple_installments()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'active',
            'months' => 12
        ]);

        $installment1 = Installment::factory()->create([
            'loan_id' => $loan->id,
            'amount' => 1000,
            'status' => 'unpaid'
        ]);

        $installment2 = Installment::factory()->create([
            'loan_id' => $loan->id,
            'amount' => 1000,
            'status' => 'unpaid'
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.recordPayment', $loan->id), [
            'installment_ids' => [$installment1->id, $installment2->id],
            'receipt_number' => 'REC-555',
            'payment_method' => 'cash', // Note: recordPayment method does not take payment_method parameter directly for the transaction in LoanController based on current code, wait let me check
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('installments', [
            'id' => $installment1->id,
            'status' => 'paid'
        ]);

        $this->assertDatabaseHas('installments', [
            'id' => $installment2->id,
            'status' => 'paid'
        ]);
    }

    public function test_employee_can_pay_single_installment()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'active',
            'months' => 12
        ]);

        $installment = Installment::factory()->create([
            'loan_id' => $loan->id,
            'amount' => 1000,
            'status' => 'unpaid'
        ]);

        $response = $this->actingAs($this->employee)->post(route('loans.installments.pay', $installment->id), [
            'receipt_number' => 'REC-777',
            'payment_method' => 'cash',
            'receipt_image' => UploadedFile::fake()->create('receipt.pdf', 100),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('installments', [
            'id' => $installment->id,
            'status' => 'paid'
        ]);

        $this->assertDatabaseHas('transactions', [
            'reference_type' => Installment::class,
            'reference_id' => $installment->id,
            'method' => 'cash',
            'type' => 'IN'
        ]);

        // If this was the last installment, loan should be completed
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'completed'
        ]);
    }

    public function test_early_repayment_succeeds_when_less_than_6_installments_remaining()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'active',
            'months' => 12,
            'base_amount' => 12000,
            'total_amount' => 14000
        ]);

        // Create 6 remaining installments (<= 6)
        for ($i = 0; $i < 6; $i++) {
            Installment::factory()->create([
                'loan_id' => $loan->id,
                'amount' => 14000 / 12,
                'status' => 'unpaid'
            ]);
        }

        $response = $this->actingAs($this->employee)->post(route('loans.earlyRepayment', $loan->id), [
            'payment_method' => 'cash',
            'receipt_number' => 'REC-EARLY'
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'completed'
        ]);

        $this->assertDatabaseMissing('installments', [
            'loan_id' => $loan->id,
            'status' => 'unpaid'
        ]);

        // Assert early repayment transaction
        // monthly principal = 12000 / 12 = 1000
        // 6 remaining = 6000
        $this->assertDatabaseHas('transactions', [
            'reference_type' => Loan::class,
            'reference_id' => $loan->id,
            'amount' => 6000,
            'category' => 'early_repayment',
            'type' => 'IN'
        ]);
    }

    public function test_early_repayment_fails_when_more_than_6_installments_remaining()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $loan = Loan::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'active',
            'months' => 12,
            'base_amount' => 12000,
            'total_amount' => 14000
        ]);

        // Create 7 remaining installments (> 6)
        for ($i = 0; $i < 7; $i++) {
            Installment::factory()->create([
                'loan_id' => $loan->id,
                'status' => 'unpaid'
            ]);
        }

        $response = $this->actingAs($this->employee)->post(route('loans.earlyRepayment', $loan->id), [
            'payment_method' => 'cash',
            'receipt_number' => 'REC-EARLY'
        ]);

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'active'
        ]);
    }
}
