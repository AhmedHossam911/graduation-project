<?php

namespace Tests\Feature\Financial;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Financial\Transaction;

class FinancialTransactionTest extends TestCase
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
            'custom_permissions' => ['الشؤون المالية']
        ]);
    }

    public function test_employee_can_view_finance_index()
    {
        $response = $this->actingAs($this->employee)->get(route('finance.index'));
        $response->assertStatus(200);
        $response->assertViewIs('employee.finance.index');
    }

    public function test_employee_can_store_manual_transaction()
    {
        Storage::fake('public');

        $data = [
            'type' => 'IN',
            'category' => 'other_revenue',
            'method' => 'cash',
            'amount' => 500,
            'description' => 'Donation',
            'attachment' => UploadedFile::fake()->create('receipt.pdf', 100),
        ];

        $response = $this->actingAs($this->employee)->post(route('finance.store'), $data);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'type' => 'IN',
            'category' => 'other_revenue',
            'method' => 'cash',
            'amount' => 500,
            'description' => 'Donation'
        ]);
    }

    public function test_employee_can_view_transaction_details()
    {
        $transaction = Transaction::factory()->create([
            'type' => 'OUT',
            'category' => 'office_expenses',
            'method' => 'cash',
            'amount' => 250,
            'description' => 'Office supplies'
        ]);

        $response = $this->actingAs($this->employee)->get(route('finance.show', $transaction->id));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $transaction->id,
            'type' => 'OUT',
            'category' => 'office_expenses',
            'method' => 'cash',
            'amount' => '250.00',
            'description' => 'Office supplies'
        ]);
    }
}
