<?php

namespace Database\Factories\Services;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Services\Claim;
use App\Models\Services\Membership;

class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'membership_id' => Membership::factory(),
            'type' => fake()->randomElement(array_keys(Claim::CLAIM_TYPES)),
            'amount' => fake()->randomFloat(2, 5000, 50000),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'paid']),
            'attachment_receipt' => 'claims/' . fake()->uuid() . '.pdf',
        ];
    }
}
