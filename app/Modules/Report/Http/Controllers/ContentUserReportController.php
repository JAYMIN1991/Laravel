<?php

namespace App\Modules\Report\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\PermissionHelpers;
use App\Modules\Report\Http\Requests\ContentUserReportRequest;
use App\Modules\Report\Repositories\Contracts\LMSCopyContentRepo;
use App\Modules\Report\Repositories\Criteria\ContentUserReportSearchCrit;
use App\Modules\Shared\Misc\ContentUserReportViewHelper;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Http\Controllers\Controller;
use DBLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

/**
 * Class ContentUserReportController
 * Controller for content report page
 *
 * @package App\Modules\Report\Http\Controllers
 */
class ContentUserReportController extends Controller {

	/**
	 * View of content user report
	 *
	 * @param \App\Modules\Report\Http\Requests\ContentUserReportRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( ContentUserReportRequest $request ) {

		$deleted = false;

		if ( $request->has('show_deleted_course') && $request->input('show_deleted_course') == 1 ) {
			$deleted = true;
		}

		$importStatusOptions = [
			ContentUserReportViewHelper::COPY_CONTENT_JOB_NOT_STARTED => trans('report::content-user-report.index.import_status_option.not_started'),
			ContentUserReportViewHelper::COPY_CONTENT_JOB_RUNNING     => trans('report::content-user-report.index.import_status_option.running'),
			ContentUserReportViewHelper::COPY_CONTENT_JOB_COMPLETED   => trans('report::content-user-report.index.import_status_option.completed'),
			ContentUserReportViewHelper::COPY_CONTENT_JOB_FAILED      => trans('report::content-user-report.index.import_status_option.failed')
		];

		$canExport = PermissionHelpers::canExport(Session::get('user_id'));
		$copyContentRepo = App::make(LMSCopyContentRepo::class);
		$copyContentRepo->pushCriteria(App::make(ContentUserReportSearchCrit::class));

		if ( $request->has('form_submit') && $request->input('form_submit') == 'search' ) {
			list($sourceInstitute, $targetInstitute) = GeneralHelpers::getRequestData($request,
				['source_institute_id', 'target_institute_id']);

			/** @var LengthAwarePaginator $userReports */
			$userReports = $copyContentRepo->getContentUserReport($deleted, true)->appends($request->except('page'));

			/** @var UserMasterRepo $userMasterRepo */
			$userMasterRepo = App::make(UserMasterRepo::class);
			$sourceInstituteName = $userMasterRepo->getInstituteByOwnerId($sourceInstitute)['user_school_name'];

			if ( $targetInstitute ) {
				$targetInstituteName = $userMasterRepo->getInstituteByOwnerId(GeneralHelpers::clearParam($targetInstitute,
					PARAM_RAW_TRIMMED))['user_school_name'];
			}

			DBLog::save(LOG_MODULE_CONTENT_USE_REPORT, null, 'search', $request->getRequestUri(),
				Session::get('user_id'), $request->all());

		} elseif ( $canExport && $request->has('form_submit') && $request->input('form_submit') == 'export' ) {
			$userReports = $copyContentRepo->getContentUserReport($deleted, false);
			$exportColumnNames = [
				'source_course'    => 'Source Course',
				'target_course'    => 'Target Course',
				'target_inst_name' => 'Target Institute',
				'views'            => 'Views',
				'comments'         => 'Comments'
			];

			DBLog::save(LOG_MODULE_CONTENT_USE_REPORT, null, 'export', $request->getRequestUri(),
				Session::get('user_id'), $request->all());

			GeneralHelpers::exportToExcel($exportColumnNames, $userReports->all(), FILENAME_CONTENT_USER_REPORT);
		}

		return view('report::content-user-report',
			compact('importStatusOptions', 'userReports', 'sourceInstituteName', 'targetInstituteName', 'canExport'));
	}
}
