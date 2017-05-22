<?php
namespace App\Modules\Sales\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Sales\Http\Requests\Acquisition\ReportRequest;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Shared\Misc\AcquisitionReportViewHelper;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use Illuminate\Http\RedirectResponse;
use Redirect;
use View;

/**
 * Class AcquisitionController
 * @package App\Modules\Sales\Http\Controllers
 */
class AcquisitionController extends Controller {

	/**
	 * Display acquisition report
	 *
	 * @param  ReportRequest $request
	 *
	 * @return View|RedirectResponse
	 */
	public function report( ReportRequest $request ) {

		$filterData = $request->only([
			'ref_by',
			'course_user_id',
			'date_from',
			'date_to',
			'post_type',
			'post_value',
			'date_range_on'
		]);

		if ( $request->exists('button_export') ) {

			$acquisitions = App::make(SalesVisitRepo::class)->searchAcquisition($filterData, false);

			if ( $acquisitions->isEmpty() ) {

				return Redirect::back()->withErrors(trans('shared::message.error.nothing_to_export'));
			}

			$exportColumnNames = [
				'ref_by'         => trans('sales::acquisition.ref_by'),
				'institute_name' => trans('sales::acquisition.institute_name'),
				'total_users'    => trans('sales::acquisition.total_users'),
				'total_mobile'   => trans('sales::acquisition.mobile_users'),
				'total_verified' => trans('sales::acquisition.verified_users'),
				'total_posts'    => trans('sales::acquisition.post_type')
			];
			$fileName = 'institute_list';
			GeneralHelpers::exportToExcel($exportColumnNames, $acquisitions->all(), $fileName);
		}

		$refBy = App::make(SalesTeamRepo::class)->getList()->pluck('user_name', 'member_id');
		$institutes = [];
		$userId = GeneralHelpers::clearParam($request->input('course_user_id'), PARAM_RAW_TRIMMED);
		if ( ! empty($userId) ) {
			$institutes = App::make(UserMasterRepo::class)->getInstituteByOwnerId($userId);
			if ( $institutes ) {
				$institutes = [GeneralHelpers::encode($institutes['user_id']) => $institutes['user_school_name']];
			}
		}
		$totalPosts = [
			AcquisitionReportViewHelper::SELECT_OPTION_GREATER_THAN => trans('sales::acquisition.total_post_options.greater_than'),
			AcquisitionReportViewHelper::SELECT_OPTION_LESS_THAN    => trans('sales::acquisition.total_post_options.less_than'),
			AcquisitionReportViewHelper::SELECT_OPTION_EQUALS_TO    => trans('sales::acquisition.total_post_options.equals_to'),
		];
		$dateRangeOn = [
			AcquisitionReportViewHelper::SELECT_OPTION_DATE_RANGE_ON_INSTITUTE => trans('sales::acquisition.institute'),
			AcquisitionReportViewHelper::SELECT_OPTION_DATE_RANGE_ON_USER      => trans('sales::acquisition.user')
		];

		$acquisitions = App::make(SalesVisitRepo::class)
		                   ->searchAcquisition($filterData, true)
		                   ->appends($request->except('page'));

		return View::make('sales::acquisition.report', compact('acquisitions', 'refBy', 'institutes', 'totalPosts', 'dateRangeOn'));
	}
}
