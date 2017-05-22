<?php

namespace App\Modules\Shared\Providers;

use App\Modules\Shared\Repositories\BackOfficeJobResults;
use App\Modules\Shared\Repositories\Contracts\BackOfficeJobResultsRepo;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Shared\Repositories\Course;
use App\Modules\Shared\Repositories\UserMaster;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Shared\Providers
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
		$this->app->bind(UserMasterRepo::class, UserMaster::class);
		$this->app->bind(CourseRepo::class, Course::class);
        $this->app->bind(BackOfficeJobResultsRepo::class, BackOfficeJobResults::class);
        //:end-bindings:
	}
}
