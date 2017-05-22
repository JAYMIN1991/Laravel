<?php

namespace App\Modules\Location\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface StateRepository
 * @package namespace App\Modules\Location\Repositories;
 */
interface StatesRepo extends RepositoryInterface
{
	/**
	 * Get State List
	 * @return Collection
	 */
	public function getList();
}
