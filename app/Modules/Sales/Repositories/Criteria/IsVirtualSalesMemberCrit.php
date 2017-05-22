<?php

namespace App\Modules\Sales\Repositories\Criteria;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsVirtualSalesMemberCrit
 * @package namespace App\Modules\Sales\Repositories\Criteria;
 */
class IsVirtualSalesMemberCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model->where('virtual_member' , 1);
		return $model;
	}
}
