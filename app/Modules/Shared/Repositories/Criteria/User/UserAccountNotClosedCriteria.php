<?php

namespace App\Modules\Shared\Repositories\Criteria\User;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class UserAccountNotClosedCriteriaCriteria
 * @package namespace App\Modules\Users\Criteria;
 */
class UserAccountNotClosedCriteria extends AbstractCriteria {

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
			$query->where($this->getAttributeName(TABLE_USERS, "user_acc_closed"), '=', NULL)
			      ->orWhere($this->getAttributeName(TABLE_USERS, 'user_acc_closed'), '=', 0);
		});

		return $model;
	}
}
