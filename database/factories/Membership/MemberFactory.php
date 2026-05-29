<?php

namespace Database\Factories\Membership;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membership\Member;
use App\Models\Auth\User;
use App\Models\System\Department;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'full_name' => fake('ar_EG')->name(),
            'national_id' => fake()->unique()->numerify('2#############'), // 14 digits starts with 2 (e.g. 1900s) or 3 (2000s)
            'birth_date' => fake()->dateTimeBetween('-59 years', '-21 years')->format('Y-m-d'),
            'phone' => fake()->phoneNumber(),
            'address' => fake('ar_EG')->address(),
        ];
    }
}
