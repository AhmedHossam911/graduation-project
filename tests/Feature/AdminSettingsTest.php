<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\SystemSetting;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin Role (id = 1)
        $adminRole = Role::factory()->create(['id' => 1, 'name' => 'Admin']);
        // Create Member Role (id = 3)
        $memberRole = Role::factory()->create(['id' => 3, 'name' => 'Member']);

        // Create Admin User
        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'is_restricted' => false,
        ]);

        // Create Regular User
        $this->user = User::factory()->create([
            'role_id' => $memberRole->id,
            'is_restricted' => false,
        ]);
    }

    /**
     * Test guests cannot view or update settings.
     */
    public function test_guest_cannot_access_settings(): void
    {
        $this->get(route('admin.settings.index'))
            ->assertRedirect(route('login'));

        $this->post(route('admin.settings.update'), [])
            ->assertRedirect(route('login'));

        $this->post(route('admin.settings.reset'), [])
            ->assertRedirect(route('login'));
    }

    /**
     * Test non-admin users cannot access settings.
     */
    public function test_non_admin_cannot_access_settings(): void
    {
        $this->actingAs($this->user);

        $this->get(route('admin.settings.index'))
            ->assertStatus(403);

        $this->post(route('admin.settings.update'), [])
            ->assertStatus(403);

        $this->post(route('admin.settings.reset'), [])
            ->assertStatus(403);
    }

    /**
     * Test admin can access settings index page.
     */
    public function test_admin_can_access_settings_page(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('إعدادات اللائحة الأساسية');
        $response->assertSee('البيانات الأساسية للصندوق');
        $response->assertSee('الاشتراكات والرسوم');
        $response->assertSee('القروض والتمويل');
        $response->assertSee('المزايا التأمينية');
    }

    /**
     * Test admin can update system settings with valid inputs.
     */
    public function test_admin_can_update_settings(): void
    {
        $this->actingAs($this->admin);

        $formData = [
            'system_name' => 'صندوق التأمين الجديد بجامعة العاصمة',
            'retirement_age' => 65,
            'default_currency' => 'جنيه مصري (EGP)',
            'subscription_percentage' => 15,
            'employer_contribution_percentage' => 10,
            'membership_join_fee' => '[{"years":"42","fee_months":"14"}]',
            'membership_min_age' => 22,
            'membership_max_age' => 60,
            'dismissal_notice_months' => 3,
            'loan_percentage' => 80,
            'loan_interest_rate' => 10,
            'loan_max_amount' => 30000,
            'loan_repayment_months' => 48,
            'loan_min_years_subscribed' => 6,
            'claim_basic_percentage' => 150,
            'claim_transfer_resignation_percentage' => 85,
            'claim_funeral_expenses' => 4000,
            'claim_min_years_subscribed' => 8,
        ];

        $response = $this->post(route('admin.settings.update'), $formData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'تم حفظ التعديلات بنجاح.');

        // Verify values are persisted in the database
        foreach ($formData as $key => $value) {
            $this->assertDatabaseHas('system_settings', [
                'key' => $key,
                'value' => (string) $value,
            ]);
        }
    }

    /**
     * Test validation rules are enforced.
     */
    public function test_settings_validation_errors(): void
    {
        $this->actingAs($this->admin);

        // Invalid retirement age (out of bounds)
        // Invalid max age (less than min age)
        // Invalid percentage (greater than 100)
        $invalidData = [
            'system_name' => '', // Required
            'retirement_age' => 90, // max is 80
            'default_currency' => '', // Required
            'subscription_percentage' => 150, // Must be <= 100
            'employer_contribution_percentage' => -10, // Must be >= 0
            'membership_join_fee' => '', // Required string
            'membership_min_age' => 10,
            'membership_max_age' => 5, // Must be >= min_age and >= 18
            'dismissal_notice_months' => 20, // Must be <= 12
            'loan_percentage' => 150, // Must be <= 100
            'loan_interest_rate' => -1,
            'loan_max_amount' => -100,
            'loan_repayment_months' => 150, // max is 120
            'loan_min_years_subscribed' => -2,
            'claim_basic_percentage' => -5,
            'claim_transfer_resignation_percentage' => 101, // Must be <= 100
            'claim_funeral_expenses' => -100,
            'claim_min_years_subscribed' => -5,
        ];

        $response = $this->post(route('admin.settings.update'), $invalidData);

        $response->assertSessionHasErrors([
            'system_name',
            'retirement_age',
            'default_currency',
            'subscription_amount',
            'membership_join_fee',
            'membership_min_age',
            'membership_max_age',
            'loan_percentage',
            'loan_interest_rate',
            'loan_max_amount',
            'loan_repayment_months',
            'loan_min_years_subscribed',
            'claim_basic_percentage',
            'claim_transfer_resignation_percentage',
            'claim_funeral_expenses',
            'claim_min_years_subscribed',
        ]);
    }

    /**
     * Test admin can reset settings to defaults.
     */
    public function test_admin_can_reset_settings(): void
    {
        $this->actingAs($this->admin);

        // First modify a setting
        SystemSetting::updateOrCreate(
            ['key' => 'system_name'],
            ['value' => 'اسم معدل']
        );

        $this->assertDatabaseHas('system_settings', [
            'key' => 'system_name',
            'value' => 'اسم معدل',
        ]);

        $response = $this->post(route('admin.settings.reset'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'تم استعادة قيم اللائحة الأساسية بنجاح.');

        // Verify default value is restored
        $this->assertDatabaseHas('system_settings', [
            'key' => 'system_name',
            'value' => 'صندوق التأمين الخاص لأعضاء هيئة التدريس والعاملين بجامعة العاصمة',
        ]);
    }
}
