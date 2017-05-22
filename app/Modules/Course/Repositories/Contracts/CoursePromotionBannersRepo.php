<?php

namespace App\Modules\Course\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CoursePromotionBannerRepo
 * @package namespace App\Modules\Course\Repositories\Contracts;
 */
interface CoursePromotionBannersRepo extends RepositoryInterface {

	/** constant value for active banner  */
	const is_active = 1;

	/**
	 * Remove Course Promotion by course id
	 *
	 * @param $courseId
	 *
	 * @return int
	 */
	public function removePromotionLocationsByCourseId( $courseId );

	/**
	 * Get course promotion banners by id of the course
	 *
	 * @param int   $courseId
	 * @param array $attributes
	 *
	 * @return mixed
	 */
	public function getPromotionLocationsByCourseId( $courseId, array $attributes = [] );

	/**
	 * Create course promotion location
	 *
	 * @param $promotionLocationData
	 *
	 * @return mixed
	 */
	public function createPromotionLocation( $promotionLocationData );
}
