<?php

namespace App\Modules\Sales\Repositories\Criteria;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Illuminate\Database\Query\Builder;

/**
 * Class ActiveCategoryCrit
 * @package namespace App\Modules\Sales\Repositories\Criteria;
 */
class ActiveCategoryCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		/* @var \Illuminate\Database\Query\Builder $model */
		return $model->where('category_active', 1);

	}
}
