<?php

namespace Mawuekom\SecurityFeatures;

use Illuminate\Support\ServiceProvider;

class SecurityFeaturesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('security-features', function () {
            return new SecurityFeatures;
        });

        $this ->app ->register('Fruitcake\Cors\CorsServiceProvider');
    }
}
