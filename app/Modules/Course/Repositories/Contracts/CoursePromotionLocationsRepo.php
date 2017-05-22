<?php

namespace App\Modules\Course\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface CoursePromotionLocationsRepo
 * @package namespace App\Modules\Course\Repositories\Contracts;
 */
interface CoursePromotionLocationsRepo extends RepositoryInterface {

	/**
	 * Get all Course promotion locations
	 *
	 * @param array $attributes provide attributes to be return. default is promo_loc_id, location_name
	 *
	 * @return Collection Returns collection of promotion location
	 */
	public function getAllLocations( array $attributes = [] );
}
