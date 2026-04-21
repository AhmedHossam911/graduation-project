<?php

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => 'Role_' . $this->faker->unique()->word(),
            'permissions' => ['all' => true],
        ];
    }
}
