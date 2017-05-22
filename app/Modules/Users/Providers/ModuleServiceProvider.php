<?php

namespace App\Modules\Users\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

/**
 * Class ModuleServiceProvider
 * @package App\Modules\Users\Providers
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
		$this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'users');
		$this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'users');
		$this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
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