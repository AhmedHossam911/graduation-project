<?php

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\Notification;
use App\Models\Auth\User;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'message' => fake()->paragraph(),
            'read_at' => fake()->optional(0.7)->dateTimeThisMonth(),
        ];
    }
}
