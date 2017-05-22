<?php

namespace App\Modules\Sales\Repositories\Criteria;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Illuminate\Database\Query\Builder;

/**
 * Class NonInstListAcqCrit
 * @package namespace App\Modules\Sales\Repositories\Criteria;
 */
class NonInstListAcqCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		return $model->where('inst_list_acq', 0);
	}
}
