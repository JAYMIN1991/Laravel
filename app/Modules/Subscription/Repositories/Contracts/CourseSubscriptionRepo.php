<?php

namespace App\Modules\Subscription\Repositories\Contracts;

use App\Modules\Subscription\Repositories\CourseSubscription;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface CourseSubscriptionRepository
 * @package namespace App\Modules\Subscription\Repositories\Contracts;
 * @see     CourseSubscription
 */
interface CourseSubscriptionRepo extends RepositoryInterface {

	/**
	 * Get the count of users of institute
	 *
	 * @param int   $instituteId Id of institute owner
	 * @param array $roles       Array of roles
	 *
	 * @return int Count of users
	 */
	public function getInstituteUserCount( $instituteId, array $roles );

	/**
	 * Get list of users who have subscribed in from course as learner and not subscribed
	 * in to course by any role
	 *
	 * @param int $fromCourse From course Id
	 * @param int $toCourse   To course Id
	 *
	 * @return mixed
	 */
	public function getUserWithSubscription( $fromCourse, $toCourse );
}
