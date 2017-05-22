<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'join-course' => [
	        'App\Modules\Subscription\Events\Listeners\JoinCourse'
        ],
        'activate-plan' => [
	        'App\Modules\Subscription\Events\Listeners\ActivatePlan'
        ],
        'deactivate-plan' => [
	        'App\Modules\Subscription\Events\Listeners\CancelPlan'
        ],
        'verify-plan' => [
	        'App\Modules\Subscription\Events\Listeners\VerifyPlan'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
