<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

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
        if (config('app.env') === 'production' || 
            str_contains(request()->header('Host'), 'railway.app') || 
            str_contains(request()->header('Host'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }
    }
}
