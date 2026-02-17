<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Define feature-specific permissions
        Gate::define('manage-feature', function ($user, $feature, $permission) {
            return $user->hasFeaturePermission($permission, $feature);
        });
        if (env('APP_ENV') === 'production') { // Optional: apply only in production
            URL::forceScheme('https');
        }
    }
}
