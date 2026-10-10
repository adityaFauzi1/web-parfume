<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

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
    public function boot(UrlGenerator $url): void
    {
        // Render / proxy HTTPS: paksa semua URL & asset jadi https saat production
        // agar tidak mixed-content di balik TLS terminator.
        if ($this->app->environment('production')) {
            $url->forceScheme('https');
        }
    }
}

