<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ensure compatibility with older MySQL index length limits
        Schema::defaultStringLength(191);
        
       /* if (app()->environment('production')) {
        URL::forceScheme('https');
    }*/
    }
}

