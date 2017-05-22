<?php

namespace App\Modules\Course\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class SearchOfflinePaymentCrit
 * @package namespace App\Modules\Course\Repositories\Criteria;
 */
class SearchOfflinePaymentCrit extends AbstractCriteria {

	protected $request;

	/**
	 * SearchOfflinePaymentCrit constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		if ( $this->request->has('institute_id') ) {
			$model->where(TABLE_USERS . '.user_id', '=', GeneralHelpers::clearParam(GeneralHelpers::decode($this->request->get('institute_id')), PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('course_id') ) {
			$model->where(TABLE_COURSES . '.course_id', '=', GeneralHelpers::clearParam(GeneralHelpers::decode($this->request->get('course_id')), PARAM_RAW_TRIMMED));
		}

//		if ( $this->request->has('cheque_no') ) {
//			$model->where(TABLE_PAY_OFFLINE . '.instrument_no', '=', GeneralHelpers::clearParam($this->request->get('cheque_no'), PARAM_RAW_TRIMMED));
//		}
//
//		if ( ! empty($this->request->get('date_from')) && empty($this->request->get('date_to')) ) {
//			$model->whereBetween(TABLE_PAY_OFFLINE . '.instrument_date', [
//				GeneralHelpers::saveFormattedDate($this->request->get('date_from')),
//				"0000-00-00"
//			]);
//		}
//		if ( empty($this->request->get('date_from')) && ! empty($this->request->get('date_to')) ) {
//			$model->whereBetween(TABLE_PAY_OFFLINE . '.instrument_date', [
//				"0000-00-00",
//				GeneralHelpers::saveFormattedDate($this->request->get('date_to'))
//			]);
//		}
//
//		if ( ! empty($this->request->get('date_from')) && ! empty($this->request->get('date_to')) ) {
//			$model->whereBetween(TABLE_PAY_OFFLINE . '.instrument_date', [
//				GeneralHelpers::saveFormattedDate($this->request->get('date_from')),
//				GeneralHelpers::saveFormattedDate($this->request->get('date_to'))
//			]);
//		}

		return $model;
	}
}
