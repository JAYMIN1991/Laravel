<?php

namespace App\Modules\Report\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\PermissionHelpers;
use App\Modules\Report\Http\Requests\InstituteListRequest;
use App\Http\Controllers\Controller;
use App\Modules\Report\Repositories\Criteria\InstituteListSearchCrit;
use App\Modules\Sales\Repositories\Contracts\InstCategoryRepo;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Shared\Misc\InstituteListViewHelper;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Session;

/**
 * Class InstituteListController
 * @package App\Modules\Report\Http\Controllers
 */
class InstituteListController extends Controller {

	/**
	 * Index view of institute list page
	 *
	 * @param \App\Modules\Report\Http\Requests\InstituteListRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( InstituteListRequest $request ) {
		$flinntUrl = HTTP_SERVER_CATALOG;

		$backOfficeAdmin = BACKOFFICE_ADMIN_ID;

		$planStatus = [
			InstituteListViewHelper::PLAN_STATUS_VERIFICATION_PENDING => trans('report::institute-list.index.plan_status_option.pending'),
			InstituteListViewHelper::PLAN_STATUS_VERIFIED             => trans('report::institute-list.index.plan_status_option.verified'),
			InstituteListViewHelper::PLAN_STATUS_CANCELLED            => trans('report::institute-list.index.plan_status_option.cancelled'),
			InstituteListViewHelper::PLAN_STATUS_DEACTIVATED          => trans('report::institute-list.index.plan_status_option.deactivated'),
		];

		/** @var SalesTeamRepo $salesTeamRepo */
		$salesTeamRepo = App::make(SalesTeamRepo::class);

		/** @var UserMasterRepo $userMasterRepo */
		$userMasterRepo = App::make(UserMasterRepo::class);

		/** @var InstCategoryRepo $instituteCategoryRepo */
		$instituteCategoryRepo = App::make(InstCategoryRepo::class);

		// Push the search criteria
		$userMasterRepo->pushCriteria(App::make(InstituteListSearchCrit::class));

		if ( $request->has('form_submit') && $request->get('form_submit') == 'export' ) {
			DBLog::save(LOG_MODULE_INST_LIST, null, 'export', $request->getRequestUri(), Session::get('user_id'),
				$request->all());

			$columns = [
				'user_name'    => 'Name',
				'user_login'   => 'User Name',
				'reg_date'     => 'Reg. Date',
				'sales_by'     => 'Referenced By',
				'remarks'      => 'Remarks',
				'total_users'  => 'Total Users',
				'mobile_users' => 'Mobile Users'
			];

			// Get the list of institutions for report page
			$institutions = $userMasterRepo->getInstitutionsForReport(true, false);

			GeneralHelpers::exportToExcel($columns, $institutions->all(), FILENAME_INSTITUTE_LIST);
		}
		// Encrypt the column of ref_by
		$refBy = GeneralHelpers::encryptColumns($salesTeamRepo->getList('pluck'));

		// Get the list of institutions for report page
		$institutions = $userMasterRepo->getInstitutionsForReport(false, true)->appends($request->except('page'));

		// Encrypt the user_id column
		$institutions = GeneralHelpers::encryptColumns($institutions, 'user_id');

		/**
		 * If request has institute_id parameter then fetch the name of the institute from database
		 */
		if ( $request->has('institute_id') ) {
			$instituteName = $userMasterRepo->getInstituteByOwnerId($request->get('institute_id'))['user_school_name'];
		}

		// Get the list of the virtual member
		$virtualMembers = GeneralHelpers::encryptColumns($salesTeamRepo->getVirtualMembersList('pluck'));

		// List of active category
		$categories = GeneralHelpers::encryptColumns($instituteCategoryRepo->getList());

		// Check if logged in user has permission to export
		$canExport = PermissionHelpers::canExport(Session::get('user_id'));

		DBLog::save(LOG_MODULE_INST_LIST, null, 'search', $request->getRequestUri(), Session::get('user_id'),
			$request->all());

		return view('report::institute-list',
			compact('planStatus', 'refBy', 'institutions', 'flinntUrl', 'backOfficeAdmin', 'instituteName',
				'virtualMembers', 'categories', 'canExport'));
	}
}
