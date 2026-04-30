<?php

namespace Database\Factories\Services;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Services\Membership;
use App\Models\Membership\Member;
use App\Models\Auth\User;

class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'membership_number' => 'MS-' . fake()->unique()->numerify('#####'),
            'status' => fake()->randomElement(['active', 'pending', 'loan', 'pension', 'withdrawn', 'dismissed', 'unpaid_leave', 'expired', 'suspended']),
            'declaration_accepted' => fake()->boolean(95),
            'approved_by' => User::factory(),
        ];
    }
}
