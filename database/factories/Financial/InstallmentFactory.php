<?php

namespace Database\Factories\Financial;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Financial\Installment;
use App\Models\Financial\Loan;

class InstallmentFactory extends Factory
{
    protected $model = Installment::class;

    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'amount' => fake()->randomFloat(2, 200, 2000),
            'due_date' => fake()->dateTimeBetween('-6 months', '+2 years')->format('Y-m-d'),
            'status' => fake()->randomElement(['paid', 'paid', 'unpaid', 'overdue']),
            'is_prepayment' => fake()->boolean(5),
        ];
    }
}
