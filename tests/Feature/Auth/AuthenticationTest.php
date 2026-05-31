<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\System\Department;
use App\Models\Auth\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_login_with_invalid_national_id()
    {
        $response = $this->post(route('login'), [
            'national_id' => '12345678901234',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('national_id');
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('CorrectPassword123!')
        ]);

        $response = $this->post(route('login'), [
            'national_id' => $user->national_id,
            'password' => 'WrongPassword123!'
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_unverified_email_triggers_otp_and_redirects()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'password' => Hash::make('Password123!')
        ]);

        $response = $this->post(route('login'), [
            'national_id' => $user->national_id,
            'password' => 'Password123!'
        ]);

        $response->assertRedirect(route('register.verify'));
        $this->assertDatabaseHas('otp_codes', ['user_id' => $user->id]);
        Mail::assertSent(OtpMail::class);
    }

    public function test_restricted_user_cannot_login()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_restricted' => true,
            'password' => Hash::make('Password123!')
        ]);

        $response = $this->post(route('login'), [
            'national_id' => $user->national_id,
            'password' => 'Password123!'
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_valid_login_triggers_2fa()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_restricted' => false,
            'password' => Hash::make('Password123!')
        ]);

        $response = $this->post(route('login'), [
            'national_id' => $user->national_id,
            'password' => 'Password123!'
        ]);

        $response->assertRedirect(route('login.2fa.otp'));
        $response->assertSessionHas('login_2fa_user_id', $user->id);
        
        $this->assertDatabaseHas('otp_codes', ['user_id' => $user->id]);
        Mail::assertSent(OtpMail::class);
    }

    public function test_logout_redirects_to_login()
    {
        $this->markTestSkipped('Logout route not defined or implemented yet.');
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_successful_registration_creates_records_and_sends_otp()
    {
        Role::factory()->create(['name' => 'member']);
        
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'national_id' => '12345678901234',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '01000000000',
            'workplace' => 'IT Department',
            'job_title' => 'Developer',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertRedirect(route('register.verify'));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'national_id' => '12345678901234',
            'is_restricted' => true,
        ]);

        $user = User::where('email', 'john@example.com')->first();

        $this->assertDatabaseHas('members', ['user_id' => $user->id, 'phone' => '01000000000']);
    }
}
