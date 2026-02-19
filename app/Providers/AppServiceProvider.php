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
        // Forcer le schéma https si l'URL de l'app commence par https ou si nous sommes en production
        $appUrl = config('app.url') ?: env('APP_URL');
        if ($appUrl && strpos($appUrl, 'https://') === 0) {
            URL::forceScheme('https');
        }
    }
}
