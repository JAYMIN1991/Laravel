<?php

namespace App\Modules\Shared\Repositories\Criteria\User;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsUserActiveCrit
 * @package namespace App\Modules\Users\Criteria;
 */
class IsUserActiveCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model = $model->where($this->getAttributeName(TABLE_USERS, "user_is_active"), "=", 1);

		return $model;
	}
}
