<?php

namespace App\Modules\Content\Providers;

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
		'review-course' => [
			'App\Modules\Content\Events\Listeners\ReviewCourse'
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
