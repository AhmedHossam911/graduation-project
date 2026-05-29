<?php

namespace Database\Factories\Financial;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Financial\Loan;
use App\Models\Services\Membership;
use App\Models\Auth\User;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 5000, 20000);
        $months = fake()->randomElement([12, 24, 36]);

        return [
            'membership_id' => Membership::factory(),
            'total_amount' => $totalAmount,
            'months' => $months,
            'installment_amount' => $totalAmount / $months,
            'status' => fake()->randomElement(['pending', 'active', 'completed', 'rejected']),
            'approved_by' => User::factory(),
        ];
    }
}
