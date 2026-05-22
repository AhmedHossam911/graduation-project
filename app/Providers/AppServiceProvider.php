<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();
        Carbon::setLocale('ar');
        
        if (class_exists(\Laravel\Passkeys\Passkeys::class)) {
            \Laravel\Passkeys\Passkeys::useUserModel(\App\Models\Auth\User::class);
        }
    }
}
