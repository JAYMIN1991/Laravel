<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsCourseFreeCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria;
 */
class IsCourseFreeCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model = $model->where($this->getAttributeName(TABLE_COURSES, "course_is_free"), '=', 1);

		return $model;
	}
}
