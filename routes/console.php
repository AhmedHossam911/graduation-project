<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the overdue subscriptions check daily at 08:00 AM
Schedule::command('subscriptions:check-overdue')->dailyAt('08:00');
