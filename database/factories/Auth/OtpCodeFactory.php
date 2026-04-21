<?php

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\OtpCode;
use App\Models\Auth\User;

class OtpCodeFactory extends Factory
{
    protected $model = OtpCode::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code' => fake()->numerify('######'),
            'expires_at' => fake()->dateTimeBetween('now', '+15 minutes'),
            'is_used' => fake()->boolean(20),
        ];
    }
}
