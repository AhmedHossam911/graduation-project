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
        return [
            'member_id' => Member::factory(),
            'workplace' => 'Helwan University',
            'job_title' => fake()->jobTitle(),
            'join_date' => function (array $attributes) {
                $member = Member::find($attributes['member_id']);
                if ($member) {
                    $birthDate = \Carbon\Carbon::parse($member->birth_date);
                    return fake()->dateTimeBetween($birthDate->copy()->addYears(21), 'now')->format('Y-m-d');
                }
                return fake()->dateTimeBetween('-20 years', '-1 year')->format('Y-m-d');
            },
            'retirement_date' => function (array $attributes) {
                $member = Member::find($attributes['member_id']);
                if ($member) {
                    return \Carbon\Carbon::parse($member->birth_date)->addYears(60)->format('Y-m-d');
                }
                return \Carbon\Carbon::now()->addYears(10)->format('Y-m-d');
            },
            'starting_salary' => fake()->randomFloat(2, 5000, 25000),
        ];
    }
}
