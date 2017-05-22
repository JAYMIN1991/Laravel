<?php

namespace App\Modules\Shared\Repositories\Criteria\User;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsInstituteActiveCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria\User;
 */
class IsInstituteActiveCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model->where($this->getAttributeName(TABLE_USERS, 'user_institute_verified'), '=', 1)
		      ->where($this->getAttributeName(TABLE_USERS, 'user_plan_expired'), '=', 0)
		      ->where($this->getAttributeName(TABLE_USERS, 'user_plan_cancelled'), '=', 0)
		      ->where($this->getAttributeName(TABLE_USERS, 'user_plan_verified'), '=', 1);

		return $model;
	}
}
