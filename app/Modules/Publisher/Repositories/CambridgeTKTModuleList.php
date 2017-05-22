<?php

namespace App\Modules\Publisher\Repositories;

use App;
use App\Modules\Publisher\Repositories\Criteria\CambridgeTKTExamSearchCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Publisher\Repositories\Contracts\CambridgeTKTModuleListRepo;

/**
 * Class CambridgeTKTModuleList
 * @package namespace App\Modules\Publisher\Repositories;
 * @see     CambridgeTKTModuleList
 */
class CambridgeTKTModuleList extends BaseRepository implements CambridgeTKTModuleListRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'id';

	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_LEARN_TKT_TESTS;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * @return mixed
	 */
	// set collection for module list
	public function getCambridgeTKTExamModuleList() {
		$result = $this->select(DB::raw('DISTINCT(test_name)'))->where('is_active', '=', '1')->get();

		return $this->parserResult($result);
	}

	// get collection for city list
	public function getCambridgeTKTExamCityList() {
		$result = $this->select(DB::raw('DISTINCT(test_location)'))->where('is_active', '=', '1')->get();

		return $this->parserResult($result);
	}

	// get search result for cambridge tkt exam
	/**
	 * @param bool $pagination
	 *
	 * @return \App\Modules\Publisher\Repositories\CambridgeTKTModuleList|\Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getCambridgeTKTExamSearch( $pagination = false ) {
		$this->pushCriteria(App::make(CambridgeTKTExamSearchCrit::class));

		$this->select(DB::raw('id, test_name, test_location, test_date, test_url'))->orderBy('id', 'DESC');

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this;
		}

		return $result;
	}

	// insert cambridge tkt exam
	/**
	 * @param $CambridgeTKTExamData
	 *
	 * @return bool
	 */
	public function insertCambridgeTKTExamData( $CambridgeTKTExamData ) {
		$result = $this->insert($CambridgeTKTExamData);

		return $result;
	}

	// get collection for exam result using id
	/**
	 * @param $CambridgeTKTExamRequestId
	 *
	 * @return array|null|\stdClass
	 */
	public function getCambridgeTKTExamData( $CambridgeTKTExamRequestId ) {

		if ( $CambridgeTKTExamRequestId ) {
			$result = $this->select('*')->where('id', '=', $CambridgeTKTExamRequestId)->first();
		}

		return $result;
	}

	// update cambridge tkt exam result.
	/**
	 * @param $CambridgeTKTExamUpdateData
	 * @param $CambridgeTKTExamRequestId
	 *
	 * @return mixed
	 */
	public function CambridgeTKTExamDataUpdate( $CambridgeTKTExamUpdateData, $CambridgeTKTExamRequestId ) {

		if ( ! empty($CambridgeTKTExamUpdateData) ) {
			$result = $this->updateById($CambridgeTKTExamUpdateData, $CambridgeTKTExamRequestId);
		}

		return $result;
	}
}
