<?php

namespace App\Modules\Report\Repositories\Criteria;

use App\Common\GeneralHelpers;
use App\Common\URLHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Http\Request;

/**
 * Class NewUserSearchCrit
 * @package namespace App\Modules\Report\Repositories\Criteria;
 */
class NewUserSearchCrit extends AbstractCriteria {

	protected $request;

	/**
	 * InstituteUsersListSearchCrit constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$dateFilter = 'DATE(FROM_UNIXTIME(user_term_dt)) BETWEEN ? AND ?';

		// Apply filter if from and to both date are present
		if ( $this->request->has('date_from') && $this->request->has('date_to') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam(URLHelpers::decodeGetParam($this->request->input('date_from')),
				PARAM_RAW_TRIMMED));
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam(URLHelpers::decodeGetParam($this->request->input('date_to')),
				PARAM_RAW_TRIMMED));
			$model->whereRaw($dateFilter, [$fromDate, $toDate]);
		} // Apply filter if from date is present
		elseif ( $this->request->has('date_from') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam(URLHelpers::decodeGetParam($this->request->input('date_from')),
				PARAM_RAW_TRIMMED));
			$toDate = (string) Helper::getDate(trans('shared::config.mysql_date_format'));
			$model->whereRaw($dateFilter, [$fromDate, $toDate]);
		} // Apply filter if to date is present
		elseif ( $this->request->has('date_to') ) {
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam(URLHelpers::decodeGetParam($this->request->input('date_to')),
				PARAM_RAW_TRIMMED));
			$model->whereRaw($dateFilter, ['0000-00-00', $toDate]);
		}

		if ( $this->request->has('first_name') ) {
			$firstName = GeneralHelpers::clearParam($this->request->input('first_name'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_firstname', 'LIKE', $firstName . '%');
		}

		if ( $this->request->has('last_name') ) {
			$lastName = GeneralHelpers::clearParam($this->request->input('last_name'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_lastname', 'LIKE', $lastName . '%');
		}

		if ( $this->request->has('user_name') ) {
			$userName = GeneralHelpers::clearParam($this->request->input('user_name'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_login', 'LIKE', '%' . $userName . '%');
		}


		return $model;
	}
}
