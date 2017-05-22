<?php

namespace App\Modules\Sales\Providers;

use App\Modules\Sales\Repositories\AfterSalesVisit;
use App\Modules\Sales\Repositories\Contracts\AfterSalesVisitRepo;
use Illuminate\Support\ServiceProvider;
use App\Modules\Sales\Repositories\Contracts\InstCategoryRepo;
use App\Modules\Sales\Repositories\InstCategory;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\InstInquiry;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Sales\Repositories\SalesTeam;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Sales\Repositories\SalesVisit;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Sales\Providers
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
     * Register the application services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InstCategoryRepo::class, InstCategory::class);
        $this->app->bind(InstInquiryRepo::class, InstInquiry::class);
        $this->app->bind(SalesTeamRepo::class, SalesTeam::class);
        $this->app->bind(SalesVisitRepo::class, SalesVisit::class);
        $this->app->bind(AfterSalesVisitRepo::class, AfterSalesVisit::class);
        //:end-bindings:
    }
}
