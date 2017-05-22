<?php

namespace App\Modules\Sales\Repositories\Criteria;

use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class NonDeletedCriteria
 * @package namespace App\Criteria;
 */
class NonDeletedCrit implements CriteriaInterface {

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		return $model->where('is_deleted', 0);
	}
}
