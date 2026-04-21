<?php

namespace Database\Factories\System;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\System\Department;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word() . ' Department',
        ];
    }
}
