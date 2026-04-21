<?php

namespace Database\Factories\System;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\System\SystemSetting;

class SystemSettingFactory extends Factory
{
    protected $model = SystemSetting::class;

    public function definition(): array
    {
        return [
            'key' => 'config_' . fake()->unique()->word(),
            'value' => fake()->text(50),
        ];
    }
}
