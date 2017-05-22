<?php

namespace App\Modules\Sales\Repositories;

use App;
use App\Modules\Sales\Repositories\Contracts\InstCategoryRepo;
use App\Modules\Sales\Repositories\Criteria\ActiveCategoryCrit;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;

/**
 * Class InstCategoryRepositoryEloquent
 * @package namespace App\Modules\Sales\Repositories;
 */
class InstCategory extends BaseRepository implements InstCategoryRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'category_id';

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_BACKOFFICE_INST_CATEGORY;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(App::make(ActiveCategoryCrit::class));
	}

	/**
	 * Return list of active backoffice institute categories
	 *
	 * @return Collection
	 */
	public function getList() {
		$results = $this->orderBy('category_id')->pluck('category_name', 'category_id');

		return $this->parserResult($results);
	}
}
