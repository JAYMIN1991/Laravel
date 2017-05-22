<?php

namespace App\Modules\Location\Repositories;

use App\Modules\Location\Repositories\Contracts\StatesRepo;
use App\Modules\Location\Repositories\Criteria\StateDefaultCountryCrit;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;

/**
 * Class StateRepositoryEloquent
 * @package namespace App\Modules\Location\Repositories;
 */
class States extends BaseRepository implements StatesRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'state_id';

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_STATES;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(StateDefaultCountryCrit::class);
	}

	/**
	 * Get State List or default country set within Criteria
	 *
	 * @return Collection
	 */
	public function getList() {
		$result = $this->orderBy('state_id')->pluck('state_name', 'state_id');

		return $this->parserResult($result);
	}
}
