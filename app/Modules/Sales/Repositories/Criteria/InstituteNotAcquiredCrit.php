<?php

namespace App\Modules\Sales\Repositories\Criteria;

use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Query\Builder;

/**
 * Class InstInquiryNotAcquiredCriteria
 * @package namespace App\Modules\Sales\Criteria;
 */
class InstituteNotAcquiredCrit implements CriteriaInterface {

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		return $model->where('acq_status', 0);
	}

}
