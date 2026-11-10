<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
    public function boot(): void
    {
        // Em produção o site é servido por HTTPS atrás de proxy (Cloudflare): gera links seguros.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
