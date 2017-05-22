<?php

namespace App\Modules\Shared\Repositories\Criteria\User;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class IsUserInstituteCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria;
 */
class IsUserInstituteCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$model->whereRaw('IFNULL(' . TABLE_USERS . '.user_plan_id, 0) > 0')
		      ->whereRaw("IFNULL(" . TABLE_USERS . ".user_school_name,'') <> ''");

		return $model;
	}
}
