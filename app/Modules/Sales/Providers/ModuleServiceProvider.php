<?php

namespace App\Modules\Sales\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

/**
 * Class ModuleServiceProvider
 * @package App\Modules\Sales\Providers
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'sales');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'sales');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
