<?php

namespace App\Modules\Shared\Repositories\Criteria;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class IsCourseCodeActiveCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria;
 */
class IsCourseCodeActiveCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model = $model->where(function ( $query ) {

			/** @var Builder $query */
			$query->where(TABLE_COURSE_CODES . '.code_is_enabled', '=', 1)
			      ->where(TABLE_COURSE_CODES . '.code_is_cancelled', '=', 0);
		});

		return $model;
	}
}
