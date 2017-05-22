<?php

namespace App\Modules\Course\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Course\Repositories\Contracts\CoursePromotionLocationsRepo;
use Illuminate\Support\Collection;

/**
 * Class CoursePromotionLocations
 * @package namespace App\Modules\Course\Repositories;
 */
class CoursePromotionLocations extends BaseRepository implements CoursePromotionLocationsRepo
{

   /**
   	 * Primary Key
   	 * @var String
   	 */
   	protected $primaryKey = 'promo_loc_id';

    /**
     * Specify table name
     *
     * @return string
     */
    public function model()
    {
        return TABLE_COURSE_PROMO_LOCATIONS;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(){
    }

	/**
	 * Get all Course promotion locations
	 *
	 * @param array $attributes provide attributes to be return. default is promo_loc_id, location_name
	 *
	 * @return Collection Returns collection of promotion location
	 */
	public function getAllLocations( array $attributes = []){
		$defaultAttributes = ['promo_loc_id', 'location_name'];

		if(!empty($attributes)){
			$defaultAttributes = array_merge($defaultAttributes, $attributes);
		}

		$this->orderBy('promo_loc_id');

		$result = $this->all($defaultAttributes);

    	return $this->parserResult($result);
    }
}
