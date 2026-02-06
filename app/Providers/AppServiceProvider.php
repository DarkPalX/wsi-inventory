<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Validator::extend('custom_date', function ($attribute, $value, $parameters, $validator) {
            // Check if the value matches the custom format (e.g., 'YYYY-MM-DD' with 0 values allowed)
            return preg_match('/^\d{4}-(0[1-9]|1[0-2]|00)-([0-2][0-9]|3[01]|00)$/', $value);
        });
    }
}
