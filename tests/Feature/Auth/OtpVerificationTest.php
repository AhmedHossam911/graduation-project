<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\OtpCode;
use Carbon\Carbon;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_2fa_otp_can_be_verified()
    {
        $role = Role::factory()->create(['name' => 'member']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $otpCode = '123456';
        
        OtpCode::factory()->create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'is_used' => false,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        session(['login_2fa_user_id' => $user->id]);

        $response = $this->post(route('login.2fa.otp.verify'), ['code' => $otpCode]);

        $response->assertRedirect(route('profile.index'));
        $this->assertAuthenticatedAs($user);
        
        $this->assertDatabaseHas('otp_codes', [
            'user_id' => $user->id,
            'code' => $otpCode,
            'is_used' => true
        ]);
    }

    public function test_expired_otp_is_rejected()
    {
        $user = User::factory()->create();
        $otpCode = '123456';
        
        OtpCode::factory()->create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'is_used' => false,
            'expires_at' => Carbon::now()->subMinutes(1)
        ]);

        session(['login_2fa_user_id' => $user->id]);

        $response = $this->post(route('login.2fa.otp.verify'), ['code' => $otpCode]);

        $response->assertSessionHas('error');
        $this->assertGuest();
        
        $this->assertDatabaseHas('otp_codes', [
            'user_id' => $user->id,
            'code' => $otpCode,
            'is_used' => false
        ]);
    }

    public function test_used_otp_is_rejected()
    {
        $user = User::factory()->create();
        $otpCode = '123456';
        
        OtpCode::factory()->create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'is_used' => true,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        session(['login_2fa_user_id' => $user->id]);

        $response = $this->post(route('login.2fa.otp.verify'), ['code' => $otpCode]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_invalid_otp_is_rejected()
    {
        $user = User::factory()->create();
        
        OtpCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'is_used' => false,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        session(['login_2fa_user_id' => $user->id]);

        $response = $this->post(route('login.2fa.otp.verify'), ['code' => '654321']);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }
}
