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
use App\Models\Services\Claim;

class ClaimWorkflowTest extends TestCase
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
            'custom_permissions' => ['إدارة المطالبات', 'إدارة الأعضاء']
        ]);
    }

    public function test_employee_can_view_claims_index()
    {
        $response = $this->actingAs($this->employee)->get(route('claims.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employee.claims.index');
    }

    public function test_employee_can_store_claim()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        
        EmploymentInfo::factory()->create([
            'member_id' => $member->id,
            'starting_salary' => 5000,
        ]);

        $response = $this->actingAs($this->employee)->post(route('members.storeClaim', $member->id), [
            'claim_type' => 'retirement',
            'claim_documents' => [
                'some_doc' => UploadedFile::fake()->create('doc.pdf', 100),
            ]
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('claims', [
            'membership_id' => $membership->id,
            'type' => 'retirement',
            'status' => 'pending'
        ]);

        $claim = Claim::where('membership_id', $membership->id)->first();
        
        $this->assertDatabaseHas('attachments', [
            'member_id' => $member->id,
            'type' => "claim_{$claim->id}_some_doc"
        ]);
    }

    public function test_employee_can_approve_claim()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $claim = Claim::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'pending',
            'amount' => 50000
        ]);

        $response = $this->actingAs($this->employee)->post(route('claims.approve', $claim->id), [
            'receipt_number' => 'CHK-12345',
            'receipt_file' => UploadedFile::fake()->create('receipt.pdf', 100),
            'amount' => 50000
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'approved',
            'amount' => 50000
        ]);

        $this->assertDatabaseHas('transactions', [
            'reference_type' => Claim::class,
            'reference_id' => $claim->id,
            'amount' => 50000,
            'type' => 'OUT',
            'category' => 'claim_payment'
        ]);
    }

    public function test_employee_can_finalize_claim()
    {
        Storage::fake('public');

        $member = Member::factory()->create();
        $membership = Membership::factory()->create(['member_id' => $member->id]);
        $claim = Claim::factory()->create([
            'membership_id' => $membership->id,
            'status' => 'approved',
            'amount' => 50000
        ]);

        $response = $this->actingAs($this->employee)->post(route('claims.finalize', $claim->id), [
            'signed_receipt' => UploadedFile::fake()->create('signed.pdf', 100),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('claims', [
            'id' => $claim->id,
            'status' => 'paid',
        ]);
        
        $claim->refresh();
        $this->assertNotNull($claim->attachment_receipt);
    }
}
