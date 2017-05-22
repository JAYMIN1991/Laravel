<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use App\Common\GeneralHelpers;
use DB;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class CourseReviewSearchCrit
 * @package namespace App\Modules\Content\Repositories\Criteria;
 */
class CourseReviewSearchCrit extends AbstractCriteria {

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * RequestCriteria constructor
	 *
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param Builder             $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		/* Apply institute filter */
		if ( $this->request->has('institute') ) {
			$instituteId = GeneralHelpers::decode($this->request->input('institute'));
			$model->where(TABLE_COURSES . '.course_owner', '=', $instituteId);
		}

		/* Apply course name filter */
		if ( $this->request->has('course_name') ) {
			$courseName = GeneralHelpers::clearParam($this->request->input('course_name'), PARAM_RAW_TRIMMED);

			if ( ! empty($courseName) ) {
				$model->where(TABLE_COURSES . '.course_name', 'LIKE', '%' . $courseName . '%');
			}
		}

		/* Apply course type filter */
		if ( $this->request->has('course_type') ) {
			$courseType = GeneralHelpers::clearParam($this->request->input('course_type'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_COURSES . '.course_public_type_id', '=', $courseType);
		}

		/* Apply course review status filter */
		if (! empty($this->request->input('course_review_status')) ) {
			$courseReviewStatus = GeneralHelpers::clearParam($this->request->input('course_review_status'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_COURSES . '.course_review_status', '=', $courseReviewStatus);
		}else if (!$this->request->exists('course_review_status')) {
			$model->where(TABLE_COURSES . '.course_review_status', '=', COURSE_REVIEW_PENDING);
		}


		/* Apply date range */
		$dateTo = $dateFrom = null;
		if ( $this->request->has('date_from') ) {
			$dateFrom = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_from'), PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('date_to') ) {
			$dateTo = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_to'), PARAM_RAW_TRIMMED));
		}

		if ( ! empty($dateFrom) && empty($dateTo) ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_COURSES . '.course_start_date))'), '>=', $dateFrom);
		} else if ( ! empty($dateTo) && empty($dateFrom) ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_COURSES . '.course_start_date))'), '<=', $dateTo);
		} else if( !empty($dateTo) && !empty($dateFrom) ){
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_COURSES . '.course_start_date))'), [
				$dateFrom,
				$dateTo
			]);
		}

		return $model;
	}
}
