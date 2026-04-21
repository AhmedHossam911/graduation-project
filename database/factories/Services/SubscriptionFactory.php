<?php

namespace Database\Factories\Services;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Services\Subscription;
use App\Models\Services\Membership;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'membership_id' => Membership::factory(),
            'amount' => fake()->randomElement([150.00, 200.00, 250.00, 300.00]),
            'due_date' => fake()->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
            'status' => fake()->randomElement(['paid', 'paid', 'paid', 'unpaid', 'overdue']),
        ];
    }
}
