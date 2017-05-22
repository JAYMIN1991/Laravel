<?php

namespace App\Modules\Location\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface CountryRepository
 * @package namespace App\Modules\Location\Repositories;
 */
interface CountryRepo extends RepositoryInterface
{
	/**Get Country List
	 * @return Collection
	 */
	function getCountryList();
}
