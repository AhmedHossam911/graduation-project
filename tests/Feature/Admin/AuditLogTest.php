<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\AuditLog;

class AuditLogTest extends TestCase
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
            'custom_permissions' => ['سجل العمليات']
        ]);
    }

    public function test_admin_can_view_audit_logs()
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'test_action',
            'table_name' => 'users',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.auditlog.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.auditlog.index');
        $response->assertSee('test_action');
    }

    public function test_admin_can_search_audit_logs_by_action()
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'specific_action_123',
            'table_name' => 'users',
            'ip_address' => '127.0.0.1'
        ]);

        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'other_action',
            'table_name' => 'users',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.auditlog.index', ['search' => 'specific_action_123']));
        
        $response->assertStatus(200);
        $response->assertSee('specific_action_123');
        $response->assertDontSee('other_action');
    }
}
