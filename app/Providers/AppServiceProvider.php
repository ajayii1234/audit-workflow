<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; // ✅ THIS is the missing import

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
