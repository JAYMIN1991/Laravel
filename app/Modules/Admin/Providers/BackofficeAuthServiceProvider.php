<?php

namespace App\Modules\Admin\Providers;

use App\Modules\Admin\Auth\BackofficeDatabaseUserProvider;
use Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class BackofficeAuthServiceProvider
 *
 * @package App\Modules\Login\Providers
 */
class BackofficeAuthServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Auth::provider('backofficeauth', function () {
			return new BackofficeDatabaseUserProvider($this->app['db']->connection(), $this->app['hash'], TABLE_ADMIN_USERS);
		});
	}
}
