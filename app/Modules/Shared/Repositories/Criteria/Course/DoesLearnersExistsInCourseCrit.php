<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use App\Modules\Shared\Repositories\Course;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class DoesLearnersExistsInCourseCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria;
 */
class DoesLearnersExistsInCourseCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		$model->whereExists(function ( $query ) {
			/** @var Builder $query */
			$query->select($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_id'))
			      ->from(TABLE_USER_COURSES)
			      ->whereColumn($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_course_id'),
				      '=',
				      TABLE_COURSES . '.course_id'
			      )
			      ->where($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_expired'), '=', 0)
			      ->where($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_is_active'), '=', 1)
			      ->where($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_role_id'),
				      '=',
				      Course::USER_COURSE_ROLE_LEARNER
			      )
			      ->whereIn($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_user_id'), function ( $query ) {
				      /** @var Builder $query */
				      $query->select($this->getAttributeName(TABLE_USERS, 'user_id'))
				            ->from(TABLE_USERS)
				            ->whereColumn($this->getAttributeName(TABLE_USERS, 'user_id'),
					            '=',
					            TABLE_USER_COURSES . '.user_mod_user_id'
				            )
				            ->where($this->getAttributeName(TABLE_USERS, "user_is_active"), "=", 1)
					        ->where(function ( $query ) {
						        /** @var Builder $query */
							    $query->whereNull($this->getAttributeName(TABLE_USERS, "user_acc_closed"))
							          ->orWhere($this->getAttributeName(TABLE_USERS, 'user_acc_closed'), '=', 0);
					        });
				});
		});

		return $model;
	}
}
