<?php

namespace App\Modules\Sales\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface InstCategoryRepository
 * @package namespace App\Modules\Sales\Repositories;
 */
interface InstCategoryRepo extends RepositoryInterface
{

	/**
	 * Get Active list of BackOffice Institute Categories
	 *
	 * @return Collection
	 */
	public function getList();
}
