<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Providers\Filament\AdminPanelProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AdminPanelProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || config('app.force_https')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
