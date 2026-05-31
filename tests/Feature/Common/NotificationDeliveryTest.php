<?php

namespace Tests\Feature\Common;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Notification;

class NotificationDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $role = Role::factory()->create(['name' => 'employee']);
        $this->user = User::factory()->create([
            'role_id' => $role->id,
            'is_restricted' => false
        ]);
    }

    public function test_user_can_view_notifications()
    {
        Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification.'
        ]);

        $response = $this->actingAs($this->user)->get(route('notifications.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('common.notifications.index');
        $response->assertSee('Test Notification');
    }

    public function test_user_can_mark_notification_as_read()
    {
        $notification = Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification.',
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)->post(route('notifications.read', $notification->id));
        
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
            'read_at' => null
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification 1',
            'message' => 'This is a test notification 1.',
            'read_at' => null
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification 2',
            'message' => 'This is a test notification 2.',
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user)->post(route('notifications.read-all'));
        
        $response->assertSessionHas('success');
        
        $this->assertEquals(0, Notification::where('user_id', $this->user->id)->whereNull('read_at')->count());
    }

    public function test_user_can_delete_notification()
    {
        $notification = Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification.'
        ]);

        $response = $this->actingAs($this->user)->delete(route('notifications.destroy', $notification->id));
        
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }

    public function test_user_can_clear_all_notifications()
    {
        Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification 1',
            'message' => 'This is a test notification 1.'
        ]);

        $response = $this->actingAs($this->user)->delete(route('notifications.clear'));
        
        $response->assertSessionHas('success');
        
        $this->assertEquals(0, Notification::where('user_id', $this->user->id)->count());
    }
}
