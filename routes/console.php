<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the overdue subscriptions check hourly
Schedule::command('subscriptions:check-overdue')->hourly();

// Run the overdue installments check hourly
Schedule::command('installments:check-overdue')->hourly();

// Generate annual subscriptions for active members daily at 01:00 AM
Schedule::command('subscriptions:generate-annual')->dailyAt('01:00');

// Check for members who reached retirement age daily at 02:00 AM
Schedule::command('memberships:check-retirement')->dailyAt('02:00');
