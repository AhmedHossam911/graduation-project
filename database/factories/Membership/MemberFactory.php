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
            'birth_date' => fake()->dateTimeBetween('-59 years', '-21 years')->format('Y-m-d'),
            'phone' => fake()->phoneNumber(),
            'address' => fake('ar_EG')->address(),
        ];
    }
}
