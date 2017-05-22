<?php

namespace App\Modules\Publisher\Repositories;

use App\Modules\Publisher\Repositories\Criteria\CambridgeRegistrationsCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Publisher\Repositories\Contracts\CambridgeRegistrationRepo;

/**
 * Class CambridgeRegistration
 * @package namespace App\Modules\Publisher\Repositories;
 */
class CambridgeRegistration extends BaseRepository implements CambridgeRegistrationRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'reg_id';

	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_CELAT_REGISTRATIONS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		//$this->pushCriteria(app(RequestCriteria::class));
	}

	public function getCambridgeRegistrationSearch( $pagination = false ) {
		// Push search input criteria
		$this->pushCriteria(app(CambridgeRegistrationsCrit::class));

		return $this->getCambridgeRegistrationRecord($pagination);
	}


	/**
	 * @param bool $pagination
	 *
	 * @return mixed
	 */
	public function getCambridgeRegistrationRecord( $pagination = false, $regId = null ) {
		$cambridgeRegistrationBuilder = $this->select([
			'reg_id',
			'reg_name',
			'reg_email',
			'reg_mobile',
			'reg_institute',
			'reg_designation',
			'reg_experience',
			'reg_date',
			DB::raw('DATE_FORMAT(FROM_UNIXTIME(reg_date), "%d/%m/%Y") `reg_date_text`')
		]);
		if ( $regId ) {
			$cambridgeRegistrationBuilder->where('reg_id', '=', $regId);
		} else {
			$cambridgeRegistrationBuilder->orderBy('reg_id', 'DESC');
		}

		$result = ($pagination) ? $cambridgeRegistrationBuilder->paginate(PAGINATION_RECORD_COUNT) : $cambridgeRegistrationBuilder->get();

		return $this->parserResult($result);
	}

	/**
	 * Get registration category list
	 * @return array
	 */
	public function getCambridgeRegistrationNameList() {
		$this->select(['reg_id', DB::raw('CONCAT(reg_name, " - ", reg_mobile)')])->orderBy('reg_id', 'DESC');

		return $result = $this->all();
	}
}
