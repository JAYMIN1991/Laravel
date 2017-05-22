<?php

namespace App\Modules\Report\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\PermissionHelpers;
use App\Common\URLHelpers;
use App\Modules\Report\Http\Requests\NewUsersRequest;
use App\Modules\Report\Http\Requests\UserCountRequest;
use App\Modules\Report\Repositories\Criteria\NewUserSearchCrit;
use App\Modules\Report\Repositories\Criteria\UserCountSearchCrit;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Users\Repositories\Contracts\DeviceRegistrationRepo;
use DBLog;
use Helper;
use App\Http\Controllers\Controller;
use Session;

/**
 * Class UsersCountController
 * @package App\Modules\Report\Http\Controllers
 */
class UsersCountController extends Controller {

	/**
	 * @param \App\Modules\Report\Http\Requests\UserCountRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( UserCountRequest $request ) {
		$fromDate = $toDate = null;

		DBLog::save(LOG_MODULE_USER_COUNT_LIST, NULL, 'search', $request->getRequestUri(), Session::get('user_id'),
			$request->all());

		if ( $request->has('date_from') ) {
			$fromDate = URLHelpers::encodeGetParam($request->get('date_from'));
		}

		if ( $request->has('date_to') ) {
			$toDate = URLHelpers::encodeGetParam($request->get('date_to'));
		}

		/** @var UserMasterRepo $userMasterRepo */
		$userMasterRepo = App::make(UserMasterRepo::class);
		$totalUsers = $userMasterRepo->getActiveUsersCount();

		$userMasterRepo->pushCriteria(App::make(UserCountSearchCrit::class));
		$newUsers = $userMasterRepo->getActiveUsersCount();

		/** @var DeviceRegistrationRepo $deviceRegistrationRepo */
		$deviceRegistrationRepo = App::make(DeviceRegistrationRepo::class);
		$mobileUsers = $deviceRegistrationRepo->getTotalUsers();

		$activeUsers = 0;
		$activeUsersPercentage = round(($activeUsers * 100) / $totalUsers);

		$todayDate = Helper::getDate(trans('shared::config.date_format'));

		return view('report::users-count',
			compact('totalUsers', 'newUsers', 'mobileUsers', 'activeUsers', 'activeUsersPercentage', 'todayDate',
				'fromDate', 'toDate'));
	}

	/**
	 * @param \App\Modules\Report\Http\Requests\NewUsersRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function newUsers( NewUsersRequest $request ) {

		/** @var UserMasterRepo $userMasterRepo */
		$userMasterRepo = App::make(UserMasterRepo::class);
		$userMasterRepo->pushCriteria(App::make(NewUserSearchCrit::class));

		DBLog::save(LOG_MODULE_NEW_USERS_LIST, NULL, 'search', $request->getRequestUri(), Session::get('user_id'),
			$request->all());

		if ( $request->has('form_submit') && $request->get('form_submit') == 'export' ) {

			DBLog::save(LOG_MODULE_NEW_USERS_LIST, NULL, 'export', $request->getRequestUri(), Session::get('user_id'),
				$request->all());

			$columns = [
				'user_name'  => 'Name',
				'user_login' => 'User Name',
				'institute'  => 'Institute',
				'courses'    => 'Courses'
			];

			$users = $userMasterRepo->getUsersForNewUserPage();

			GeneralHelpers::exportToExcel($columns, $users->all(), FILENAME_USERS_LIST);
		}

		// Check if logged in user has permission to export
		$canExport = PermissionHelpers::canExport(Session::get('user_id'));

		$users = $userMasterRepo->getUsersForNewUserPage(true)->appends($request->except('page'));

		return view('report::new-users', compact('users', 'canExport'));
	}
}
