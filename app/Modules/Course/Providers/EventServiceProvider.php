<?php

namespace App\Modules\Course\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application
	 *
	 * @var array
	 */
	protected $listen = [
		'coupon-generate' => [
			'App\Modules\Course\Events\Listeners\CouponGenerate'
		]
	];

	/**
	 * Register any events for your application
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}
}
