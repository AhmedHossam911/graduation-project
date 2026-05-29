<?php

namespace Database\Factories\Auth;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Auth\User;
use App\Models\Auth\Role;

class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;

    public function definition(): array
    {
        $domains = ['@fci.helwan.edu.eg', '@eng.helwan.edu.eg', '@commerce.helwan.edu.eg', '@med.helwan.edu.eg', '@science.helwan.edu.eg'];
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $email = strtolower($firstName . '.' . $lastName) . fake()->unique()->numerify('####') . fake()->randomElement($domains);

        return [
            'role_id' => Role::factory(),
            'name' => $firstName . ' ' . $lastName,
            'national_id' => fake()->unique()->numerify('2#############'), // 14 digits
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'is_restricted' => fake()->boolean(5),
            'last_login' => fake()->optional()->dateTimeThisYear(),
            'remember_token' => Str::random(10),
        ];
    }
}
