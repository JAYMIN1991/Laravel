<?php

namespace App\Modules\Course\Providers;

use App\Modules\Course\Repositories as Repository;
use App\Modules\Course\Repositories\CoursePromotionBanners;
use App\Modules\Course\Repositories\CoursePromotionLocations;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Course\Providers
 */
class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services
	 *
	 * @return void
	 */
	public function boot() {
	}

	/**
	 * Register the application services
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(Repository\Contracts\CoursePromotionLocationsRepo::class, CoursePromotionLocations::class);
		$this->app->bind(Repository\Contracts\CoursePromotionBannersRepo::class, CoursePromotionBanners::class);
		$this->app->bind(Repository\Contracts\CourseOfflinePaymentRepo::class, Repository\CourseOfflinePayment::class);
		$this->app->bind(Repository\Contracts\CourseVerifyOfflinePaymentRepo::class, Repository\CourseVerifyOfflinePayment::class);
		//:end-bindings:
	}
}
