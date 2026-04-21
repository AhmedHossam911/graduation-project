<?php

namespace Database\Factories\Membership;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membership\FamilyInfo;
use App\Models\Membership\Member;

class FamilyInfoFactory extends Factory
{
    protected $model = FamilyInfo::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'children_count' => fake()->numberBetween(0, 5),
            'spouse_name' => fake('ar_EG')->name(),
            'spouse_phone' => fake()->phoneNumber(),
            'child_name' => fake('ar_EG')->firstName(),
            'spouse_workplace' => fake()->company(),
            'child_workplace' => fake()->optional(0.3)->company(),
        ];
    }
}
