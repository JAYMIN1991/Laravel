<?php

namespace App\Modules\Subscription\Providers;

use App\Modules\Subscription\Repositories\BackOfficeCourseInvitation;
use App\Modules\Subscription\Repositories\Contracts\BackOfficeCourseInvitationRepo;
use App\Modules\Subscription\Repositories\Contracts\CourseSubscriptionRepo;
use App\Modules\Subscription\Repositories\CourseSubscription;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Subscription\Providers
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
        $this->app->bind(CourseSubscriptionRepo::class, CourseSubscription::class);
        $this->app->bind(BackOfficeCourseInvitationRepo::class, BackOfficeCourseInvitation::class);
        //:end-bindings:
    }
}
