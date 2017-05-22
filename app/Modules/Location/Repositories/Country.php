<?php

namespace App\Modules\Location\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;
use App\Modules\Location\Repositories\Contracts\CountryRepo;

/**
 * Class CountryRepositoryEloquent
 * @package namespace App\Modules\Location\Repositories;
 */
class Country extends BaseRepository implements CountryRepo
{

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'countries_id';


	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model()
	{
		return TABLE_COUNTRIES;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot()
	{
	}

	/**
	 * Get Country List
	 * @return Collection
	 */
	function getCountryList()
	{
		$results = $this->orderBy('countries_id')
						->pluck('countries_name', 'countries_id');

		return $this->parserResult($results);

		//return $this->model->orderBy('countries_id')->pluck('countries_name','countries_id');

	}

}
