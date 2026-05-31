<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\SystemSetting;

class SystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::factory()->create(['name' => 'admin']);
        $this->admin = User::factory()->create([
            'role_id' => $role->id,
            'is_restricted' => false,
            'custom_permissions' => ['إعدادات اللائحة']
        ]);
    }

    public function test_admin_can_view_settings_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.index');
    }

    public function test_admin_can_update_settings()
    {
        $data = [
            'system_name' => 'Updated System Name',
            'retirement_age' => 65,
            'default_currency' => 'USD',
            'membership_join_fee' => '[{"years":"42 سنة فأكثر","fee_months":"15"}]',
            'membership_min_age' => 20,
            'membership_max_age' => 55,
            'dismissal_notice_months' => 2,
            'loan_percentage' => 80,
            'loan_interest_rate' => 10,
            'loan_max_amount' => 30000,
            'loan_repayment_months' => 48,
            'loan_min_years_subscribed' => 3,
            'claim_basic_percentage' => 150,
            'claim_transfer_resignation_percentage' => 85,
            'claim_funeral_expenses' => 4000,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.settings.update'), $data);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'system_name',
            'value' => 'Updated System Name'
        ]);

        $this->assertDatabaseHas('system_settings', [
            'key' => 'retirement_age',
            'value' => '65'
        ]);
    }

    public function test_admin_can_reset_settings_to_defaults()
    {
        // First change a setting
        SystemSetting::create(['key' => 'retirement_age', 'value' => '99']);

        $response = $this->actingAs($this->admin)->post(route('admin.settings.reset'));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'retirement_age',
            'value' => '60' // Default value in controller
        ]);
    }
}
