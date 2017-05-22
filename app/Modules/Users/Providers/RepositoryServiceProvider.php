<?php

namespace App\Modules\Users\Providers;

use App\Modules\Users\Repositories\Contracts\DeviceRegistrationRepo;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use App\Modules\Users\Repositories\DeviceRegistration;
use App\Modules\Users\Repositories\User;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Users\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(UserRepo::class, User::class);
		$this->app->bind(DeviceRegistrationRepo::class, DeviceRegistration::class);
        //:end-bindings:
	}
}
