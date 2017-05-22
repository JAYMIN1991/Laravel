<?php

namespace App\Modules\Location\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Location\Repositories\Contracts\CountryRepo;
use App\Modules\Location\Repositories\Country;
use App\Modules\Location\Repositories\Contracts\StatesRepo;
use App\Modules\Location\Repositories\States;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Location\Providers
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
	    $this->app->bind(CountryRepo::class, Country::class);
	    $this->app->bind(StatesRepo::class, States::class);
    	//:end-bindings:
    }
}
