<?php

namespace App\Modules\Subscription\Repositories\Criteria;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsSubscriptionActiveCrit
 * @package namespace App\Modules\Subscription\Repositories\Criteria;
 */
class IsSubscriptionActiveCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model = $model->where($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_expired'), '=', 0)
		               ->where($this->getAttributeName(TABLE_USER_COURSES, 'user_mod_is_active'), '=', 1);

		return $model;
	}
}
