<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\Department;

class UserManagementTest extends TestCase
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
            'custom_permissions' => ['إدارة الصلاحيات']
        ]);
        
        Department::factory()->create(['name' => 'Pending Registration', 'status' => 'active']);
    }

    public function test_admin_can_view_permissions_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.permissions.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.index');
    }

    public function test_non_admin_cannot_view_permissions_index()
    {
        $user = User::factory()->create([
            'is_restricted' => false,
            'custom_permissions' => []
        ]);

        $response = $this->actingAs($user)->get(route('admin.permissions.index'));
        $response->assertStatus(302);
    }

    public function test_admin_can_create_user()
    {
        $role = Role::factory()->create(['name' => 'member']);
        $department = Department::factory()->create(['name' => 'IT Department', 'status' => 'active']);

        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'national_id' => '12345678901235',
            'role_name' => 'member',
            'faculties' => ['IT Department'],
            'permissions' => []
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.store'), $data);

        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'national_id' => '12345678901235',
            'role_id' => $role->id,
            'is_restricted' => 0
        ]);
    }

    public function test_admin_can_approve_user()
    {
        $pendingUser = User::factory()->create([
            'role_id' => null,
            'is_restricted' => true
        ]);
        
        $role = Role::factory()->create(['name' => 'member']);
        Department::factory()->create(['name' => 'IT Department', 'status' => 'active']);

        $data = [
            'name' => 'Approved User',
            'email' => 'approved@example.com',
            'national_id' => '12345678901236',
            'role_name' => 'member',
            'faculties' => ['IT Department'],
            'permissions' => []
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.approve', $pendingUser), $data);

        $response->assertRedirect(route('admin.permissions.index'));

        $this->assertDatabaseHas('users', [
            'id' => $pendingUser->id,
            'email' => 'approved@example.com',
            'is_restricted' => 0,
            'role_id' => $role->id
        ]);
    }

    public function test_admin_can_suspend_user()
    {
        $activeUser = User::factory()->create([
            'is_restricted' => false
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.suspend', $activeUser));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $activeUser->id,
            'is_restricted' => 1
        ]);
    }

    public function test_admin_can_reactivate_user()
    {
        $suspendedUser = User::factory()->create([
            'is_restricted' => true
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.reactivate', $suspendedUser));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $suspendedUser->id,
            'is_restricted' => 0
        ]);
    }

    public function test_admin_can_soft_delete_user_via_reject()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.reject', $user));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted($user);
    }

    public function test_admin_can_restore_user()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs($this->admin)->post(route('admin.permissions.restore', $user->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);
    }

    public function test_admin_can_force_delete_user()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs($this->admin)->delete(route('admin.permissions.destroy', $user->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
