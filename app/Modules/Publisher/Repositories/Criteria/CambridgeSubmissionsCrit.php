<?php

namespace App\Modules\Publisher\Repositories\Criteria;

use App\Common\GeneralHelpers;
use DB;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class CambridgeSubmissionsCrit
 * @package namespace App\Modules\Publisher\Repositories\Criteria;
 */
class CambridgeSubmissionsCrit extends AbstractCriteria {

	protected $request;

	/**
	 * CambridgeSubmissionsCrit constructor.
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

		$registration = GeneralHelpers::clearParam($this->request->input('registration'), PARAM_RAW_TRIMMED);
		$activity = GeneralHelpers::clearParam($this->request->input('activity'), PARAM_RAW_TRIMMED);
		$instituteName = GeneralHelpers::clearParam($this->request->input('institute_name'), PARAM_RAW_TRIMMED);
		$category = GeneralHelpers::clearParam($this->request->input('category'), PARAM_RAW_TRIMMED);
		$place = GeneralHelpers::clearParam($this->request->input('place'), PARAM_RAW_TRIMMED);
		$fromDate = GeneralHelpers::clearParam($this->request->input('submissions_date_from'), PARAM_RAW_TRIMMED);
		$toDate = GeneralHelpers::clearParam($this->request->input('submissions_date_to'), PARAM_RAW_TRIMMED);
		// Check registration option is selected then make where for reg_id
		if ( $this->request->has('registration') ) {
			$model->where(TABLE_CELAT_REGISTRATIONS . '.reg_id', '=', $registration);
		}

		// Check activity type is selected
		if ( $this->request->has('activity') ) {
			$model->where(TABLE_CELAT_SUBMISSIONS . '.sub_activity_type', '=', $activity);
		}

		// Check institute name exist
		if ( $this->request->has('institute_name') ) {
			$model->where(TABLE_CELAT_REGISTRATIONS . '.reg_institute', 'like', '%' . $instituteName . '%');
		}

		// Check category is selected then make where condition
		if ( $this->request->has('category') ) {
			$model->where(TABLE_CELAT_SUBMISSIONS . '.sub_exam_category', '=', $category);
		}

		// Check place is added
		if ( $this->request->has('place') ) {
			$model->where(TABLE_CELAT_SUBMISSIONS . '.sub_place', '=', $place);
		}

		// Check submission start and end date
		if ( $this->request->has('submissions_date_from') && ! $this->request->has('submissions_date_to') ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_CELAT_SUBMISSIONS . '.sub_date))'), '>=', GeneralHelpers::saveFormattedDate($fromDate));
		}

		if ( $this->request->has('submissions_date_to') && ! $this->request->has('submissions_date_from') ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_CELAT_SUBMISSIONS . '.sub_date))'), '<=', GeneralHelpers::saveFormattedDate($toDate));
		}

		if ( $this->request->has('submissions_date_from') && $this->request->has('submissions_date_to') ) {
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_CELAT_SUBMISSIONS . '.sub_date))'), [
				GeneralHelpers::saveFormattedDate($fromDate),
				GeneralHelpers::saveFormattedDate($toDate)
			]);
		}

		// Return with apply where criteria
		return $model;
	}
}
