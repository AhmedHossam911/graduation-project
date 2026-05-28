<?php

namespace Database\Factories\Financial;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Financial\Transaction;
use App\Models\Services\Subscription;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'reference_type' => Subscription::class,
            'reference_id' => Subscription::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'type' => fake()->randomElement(['IN', 'OUT']),
            'method' => fake()->randomElement(['cash', 'bank_transfer', 'salary_deduction']),
            'receipt_no' => 'REC-' . fake()->unique()->numerify('######'),
        ];
    }
}
