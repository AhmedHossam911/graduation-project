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
use App\Models\Services\Subscription;

class SubscriptionPaymentTest extends TestCase
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
            'custom_permissions' => ['إدارة الاشتراكات', 'إدارة الأعضاء']
        ]);
    }

    public function test_employee_can_view_subscriptions_index()
    {
        $response = $this->actingAs($this->employee)->get(route('subscriptions.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employee.membership.index');
    }

    public function test_employee_can_pay_subscription()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $subscription = Subscription::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'unpaid',
            'amount' => 100,
            'name' => 'اشتراك عام 2025'
        ]);

        $data = [
            'payment_method' => 'cash',
            'receipt_number' => 'REC-12345',
            'receipt_image' => UploadedFile::fake()->create('receipt.pdf', 100),
        ];

        $response = $this->actingAs($this->employee)->post(route('subscriptions.pay', $subscription->id), $data);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('transactions', [
            'reference_type' => Subscription::class,
            'reference_id' => $subscription->id,
            'amount' => 100,
            'method' => 'cash',
            'receipt_no' => 'REC-12345',
            'type' => 'IN'
        ]);

        // Assert attachment was created
        $this->assertDatabaseHas('attachments', [
            'member_id' => $member->id,
            'type' => "subscription_{$subscription->id}_receipt"
        ]);
    }

    public function test_payment_of_initial_fee_generates_future_subscriptions()
    {
        $member = Member::factory()->create();
        
        // Mock employment info to trigger annual fee logic
        \App\Models\Membership\EmploymentInfo::factory()->create([
            'member_id' => $member->id,
            'starting_salary' => 5000, // Annual fee = 5000 * 3 = 15000
            'retirement_date' => now()->addYears(5)->format('Y-m-d')
        ]);

        $membership = Membership::factory()->create(['member_id' => $member->id, 'status' => 'pending_registration']);
        
        // This is the first subscription
        $subscription = Subscription::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'unpaid',
            'amount' => 1000,
            'name' => 'رسم الاشتراك بالصندوق'
        ]);

        $data = [
            'payment_method' => 'cash',
            'receipt_number' => 'REC-12345',
        ];

        $response = $this->actingAs($this->employee)->post(route('subscriptions.pay', $subscription->id), $data);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'status' => 'active'
        ]);

        // Verify that future subscriptions were generated
        $this->assertDatabaseHas('subscriptions', [
            'membership_id' => $membership->id,
            'name' => 'اشتراك عام ' . now()->year,
            'amount' => 15000, // 5000 * 3
        ]);
    }
}
