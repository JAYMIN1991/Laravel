<?php

namespace App\Modules\Account\Providers;

use App\Modules\Account\Repositories\Contracts\CourseTypeRepo;
use App\Modules\Account\Repositories\Contracts\InstituteBankRepo;
use App\Modules\Account\Repositories\Contracts\PayCommissionsRepo;
use App\Modules\Account\Repositories\Contracts\CourseOrdersSummaryRepo;
use App\Modules\Account\Repositories\Contracts\UserCommissionDiscountRepo;
use App\Modules\Account\Repositories\CourseType;
use App\Modules\Account\Repositories\CourseOrdersSummary;
use App\Modules\Account\Repositories\PayCommissions;
use App\Modules\Account\Repositories\UserCommissionDiscount;
use App\Modules\Account\Repositories\InstituteBank;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(InstituteBankRepo::class, InstituteBank::class);
        $this->app->bind(UserCommissionDiscountRepo::class, UserCommissionDiscount::class);
        $this->app->bind(CourseTypeRepo::class, CourseType::class);
        $this->app->bind(PayCommissionsRepo::class, PayCommissions::class);
        $this->app->bind(CourseOrdersSummaryRepo::class, CourseOrdersSummary::class);
        //:end-bindings:
    }
}
