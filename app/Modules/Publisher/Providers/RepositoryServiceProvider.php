<?php

namespace App\Modules\Publisher\Providers;

use App\Modules\Publisher\Repositories\CambridgeLinguaSkillSearch;
use App\Modules\Publisher\Repositories\CambridgeSubmissions;
use App\Modules\Publisher\Repositories\Contracts\CambridgeLinguaSkillSearchRepo;
use App\Modules\Publisher\Repositories\CambridgeRegistration;
use App\Modules\Publisher\Repositories\Contracts\CambridgeRegistrationRepo;
use App\Modules\Publisher\Repositories\CambridgeTKTModuleList;
use App\Modules\Publisher\Repositories\Contracts\CambridgeSubmissionsRepo;
use App\Modules\Publisher\Repositories\Contracts\CambridgeTKTModuleListRepo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(CambridgeTKTModuleListRepo::class, CambridgeTKTModuleList::class);
		$this->app->bind(CambridgeLinguaSkillSearchRepo::class, CambridgeLinguaSkillSearch::class);
		$this->app->bind(CambridgeRegistrationRepo::class, CambridgeRegistration::class);
		$this->app->bind(CambridgeSubmissionsRepo::class, CambridgeSubmissions::class);
		//:end-bindings:
	}
}
