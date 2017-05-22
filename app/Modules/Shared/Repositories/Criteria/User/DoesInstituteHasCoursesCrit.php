<?php

namespace App\Modules\Shared\Repositories\Criteria;

use DB;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class DoesInstituteHasCoursesCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria;
 */
class DoesInstituteHasCoursesCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param  BaseRepository     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model->whereExists(function ( $query ) {
			/** @var Builder $query */
			$query->select(TABLE_COURSES . '.course_id')
			      ->from(TABLE_COURSES)
			      ->where(TABLE_COURSES . '.course_owner', '=', DB::raw(TABLE_USERS . '.user_id'))
			      ->where(TABLE_COURSES . '.course_is_free', '=', 1)
			      ->where(TABLE_COURSES . '.course_enabled', '=', 1)
			      ->where(TABLE_COURSES . '.course_status', '=', COURSE_STATUS_PUBLISH);
		});

		return $model;
	}
}
