<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class PublicCourseCriteriaCriteria
 * @package namespace App\Modules\Content\Criteria;
 */
class IsPublicCourseCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model = $model->where($this->getAttributeName(TABLE_COURSES, "course_public"), '=', 1);

		return $model;
	}
}
