<?php

namespace App\Modules\Admin\Repositories\Criteria;

use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class AdminUserActiveCriteria
 * @package namespace App\Modules\Admin\Criteria;
 */
class AdminUserActiveCrit implements CriteriaInterface {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		/* @var Builder $model */
		return $model->where("user_is_active", "=", 1);
	}
}
