<?php

namespace Database\Factories\Membership;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membership\EmploymentInfo;
use App\Models\Membership\Member;

class EmploymentInfoFactory extends Factory
{
    protected $model = EmploymentInfo::class;

    public function definition(): array
    {
        $joinDate = fake()->dateTimeBetween('-20 years', '-1 year');
        $retirementDate = (clone $joinDate)->modify('+35 years');

        return [
            'member_id' => Member::factory(),
            'workplace' => 'Helwan University',
            'job_title' => fake()->jobTitle(),
            'join_date' => $joinDate->format('Y-m-d'),
            'retirement_date' => $retirementDate->format('Y-m-d'),
            'starting_salary' => fake()->randomFloat(2, 5000, 25000),
        ];
    }
}
