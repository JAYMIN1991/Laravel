<?php

namespace App\Modules\Report\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface LMSCopyContentRepo
 * @package namespace App\Modules\Report\Repositories\Contracts;
 */
interface LMSCopyContentRepo extends RepositoryInterface
{

	/**
	 * Get the report (views and comments) of copied courses
	 *
	 * @param bool $showDeleted True will show deleted course
	 * @param bool $paginate True will return LengthAwarePaginator
	 *
	 * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
	 */
	public function getContentUserReport( $showDeleted = false, $paginate = true);

}
