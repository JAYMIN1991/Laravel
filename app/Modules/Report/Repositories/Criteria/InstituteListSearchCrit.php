<?php

namespace App\Modules\Report\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class InstituteListSearchCrit
 * @package namespace App\Modules\Report\Repositories\Criteria;
 */
class InstituteListSearchCrit extends AbstractCriteria {

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
		if ( $this->request->has('institute_id') ) {
			$instituteId = (int) GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->input('institute_id'),
				PARAM_RAW_TRIMMED));
			$model->where(TABLE_USERS . '.user_id', '=', $instituteId);
		}

		if ( $this->request->has('user_login') ) {
			$userLogin = GeneralHelpers::clearParam($this->request->input('user_login'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_login', '=', $userLogin);
		}

		if ( $this->request->has('plan_status') ) {
			$planStatus = GeneralHelpers::clearParam($this->request->input('plan_status'), PARAM_RAW_TRIMMED);

			switch ( $planStatus ) {
				case 1:
					$model->where(TABLE_USERS . '.user_plan_expired', '=', 1)
					      ->where(TABLE_USERS . '.user_plan_verified', '=', 0)
					      ->where(TABLE_USERS . '.user_plan_cancelled', '=', 0);
					break;
				case 2:
					$model->where(TABLE_USERS . '.user_plan_expired', '=', 0)
					      ->where(TABLE_USERS . '.user_plan_verified', '=', 1);

					break;
				case 3:
					$model->where(TABLE_USERS . '.user_plan_expired', '=', 1)
					      ->where(TABLE_USERS . '.user_plan_verified', '=', 0)
					      ->where(TABLE_USERS . '.user_plan_cancelled', '=', 1);
					break;
				case 4:
					$model->where(TABLE_USERS . '.user_plan_expired', '=', 1)
					      ->where(TABLE_USERS . '.user_plan_verified', '=', 1);
					break;

			}
		}

		$dateFilter = TABLE_USERS . '.user_id IN 
							(SELECT sub_hist_user_id
								FROM ' . TABLE_USER_ACC_HISTORY . ' as uah
								WHERE LOWER(uah.sub_hist_action) IN (\'planactive\', \'trialplanactive\') 
									AND DATE(FROM_UNIXTIME(uah.sub_hist_dt)) BETWEEN ? AND ?)';

		// Apply filter if from and to both date are present
		if ( $this->request->has('date_from') && $this->request->has('date_to') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_from'),
				PARAM_RAW_TRIMMED));
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_to'),
				PARAM_RAW_TRIMMED));
			$model->whereRaw($dateFilter, [$fromDate, $toDate]);
		} // Apply filter if from date is present
		elseif ( $this->request->has('date_from') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_from'),
				PARAM_RAW_TRIMMED));
			$toDate = (string) Helper::getDate(trans('shared::config.mysql_date_format'));
			$model->whereRaw($dateFilter, [$fromDate, $toDate]);
		} // Apply filter if to date is present
		elseif ( $this->request->has('date_to') ) {
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_to'),
				PARAM_RAW_TRIMMED));
			$model->whereRaw($dateFilter, ['0000-00-00', $toDate]);
		}

		if ( $this->request->has('ref_by') ) {
			$refBy = GeneralHelpers::clearParam($this->request->input('ref_by'), PARAM_RAW_TRIMMED);
			$model->whereIn(TABLE_USERS . '.user_id', function ( $query ) use ( $refBy ) {
				/** @var Builder $query */
				$query->select('converted_inst_id')
				      ->from(TABLE_BACKOFFICE_INST_INQUIRY)
				      ->where('acq_member_id', '=', GeneralHelpers::decode($refBy));
			});
		}

		return $model;
	}
}
