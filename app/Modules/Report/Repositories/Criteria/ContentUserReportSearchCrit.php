<?php

namespace App\Modules\Report\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Http\Request;

/**
 * Class ContentUserReportSearchCrit
 * @package namespace App\Modules\Report\Repositories\Criteria;
 */
class ContentUserReportSearchCrit extends AbstractCriteria {

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
		$sourceInstituteId = (int) GeneralHelpers::clearParam(GeneralHelpers::decode($this->request->input('source_institute_id')),
			PARAM_RAW_TRIMMED);
		$model->where('fu.user_id', '=', $sourceInstituteId);

		if ( $this->request->has('source_course_id') ) {
			$sourceCourseId = (int) GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->input('source_course_id'),
				PARAM_RAW_TRIMMED));
			if ( $sourceCourseId > 0 ) {
				$model->where('fc.course_id', '=', $sourceCourseId);
			}
		}

		if ( $this->request->has('target_institute_id') ) {
			$targetInstituteId = (int) GeneralHelpers::clearParam($this->request->input('target_institute_id'),
				PARAM_RAW_TRIMMED);
			$model->where('tu.user_id', '=', $targetInstituteId);
		}

		if ( $this->request->has('target_course_id') ) {
			$targetCourseId = (int) GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->input('target_course_id'),
				PARAM_RAW_TRIMMED));
			if ( $targetCourseId > 0 ) {
				$model->where('fc.course_id', '=', $targetCourseId);
			}
		}

		if ( $this->request->has('course_import_status') ) {
			$importStatus = (int) GeneralHelpers::clearParam($this->request->input('course_import_status'),
				PARAM_RAW_TRIMMED);
			$model->addSelect([TABLE_LMS_COPY_CONTENT . '.job_status']);
			$model->groupBy(TABLE_LMS_COPY_CONTENT . '.job_status');
			$model->where(TABLE_LMS_COPY_CONTENT . '.job_status', '=', $importStatus - 1);
		}

		if ( $this->request->has('date_from') && $this->request->has('date_to') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_from'),
				PARAM_RAW_TRIMMED));
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_to'),
				PARAM_RAW_TRIMMED));
			$model->whereRaw('DATE(FROM_UNIXTIME(' . TABLE_LMS_COPY_CONTENT . '.job_dt)) BETWEEN ? AND ?',
				[$fromDate, $toDate]);
		} elseif ( $this->request->has('date_from') ) {
			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_from'),
				PARAM_RAW_TRIMMED));
			$toDate = (string) Helper::getDate(trans('shared::config.mysql_date_format'));
			$model->whereRaw('DATE(FROM_UNIXTIME(' . TABLE_LMS_COPY_CONTENT . '.job_dt)) BETWEEN ? AND ?',
				[$fromDate, $toDate]);
		} elseif ( $this->request->has('date_to') ) {
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('date_to'),
				PARAM_RAW_TRIMMED));
			$model->whereRaw('DATE(FROM_UNIXTIME(' . TABLE_LMS_COPY_CONTENT . '.job_dt)) BETWEEN ? AND ?',
				['0000-00-00', $toDate]);
		}

		if ( $this->request->has('greater_than_zero') && $this->request->input('greater_than_zero') > 0) {
			$model->having('views', '>', 0);
		}

		return $model;
	}
}
