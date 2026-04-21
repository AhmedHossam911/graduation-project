<?php

namespace Database\Factories\System;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\System\AuditLog;
use App\Models\Auth\User;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $tables = ['users', 'memberships', 'loans', 'installments', 'claims', 'transactions'];
        
        return [
            'user_id' => User::factory(),
            'impersonator_id' => null,
            'action' => fake()->randomElement(['Created', 'Updated', 'Deleted', 'Approved']),
            'table_name' => fake()->randomElement($tables),
            'record_id' => fake()->numberBetween(1, 1000),
            'old_values' => ['status' => 'pending'],
            'new_values' => ['status' => 'approved'],
            'ip_address' => fake()->ipv4(),
        ];
    }
}
