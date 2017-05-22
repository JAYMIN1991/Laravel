<?php

namespace App\Modules\Course\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Course\Repositories\Contracts\CoursePromotionBannersRepo;

/**
 * Class CoursePromotionBanner
 * @package namespace App\Modules\Course\Repositories;
 */
class CoursePromotionBanners extends BaseRepository implements CoursePromotionBannersRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'course_promo_loc_id';

	/**
	 * Specify table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_COURSE_PROMOTION_BANNERS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Remove Course Promotion by course id
	 *
	 * @param $courseId
	 *
	 * @return int
	 */
	public function removePromotionLocationsByCourseId( $courseId ) {
		return $this->where('course_id', $courseId)->delete();
	}

	/**
	 * Get course promotion banners by id of the course
	 *
	 * @param int   $courseId
	 * @param array $attributes
	 *
	 * @return mixed
	 */
	public function getPromotionLocationsByCourseId( $courseId, array $attributes = [] ) {
		$defaultAttributes = [
			'course_promo_loc_id',
			'location_id'
		];

		if ( ! empty($defaultAttributes) ) {
			$defaultAttributes = array_merge($defaultAttributes, $attributes);
		}

		$result = $this->where('course_id', $courseId)->get($defaultAttributes);

		return $this->parserResult($result);
	}

	/**
	 * Create course promotion banner
	 *
	 * @param $promotionLocationData
	 *
	 * @return mixed
	 */
	public function createPromotionLocation( $promotionLocationData ) {
		return $this->create($promotionLocationData);
	}
}
