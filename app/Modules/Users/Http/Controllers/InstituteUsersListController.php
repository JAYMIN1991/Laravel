<?php

namespace App\Modules\Users\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Misc\InstituteUsersListViewHelper;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Subscription\Repositories\Contracts\CourseSubscriptionRepo;
use App\Modules\Users\Http\Requests\InstituteUsersList\InstituteUsersListRequest;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use DBLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

/**
 * Class InstituteUsersListController
 * @package App\Modules\Users\Http\Controllers
 */
class InstituteUsersListController extends Controller {

	const EXPORT = 2;

	const USER_EXPORT = 3;

	/**Return the index view
	 *
	 * @param \App\Modules\Users\Http\Requests\InstituteUsersList\InstituteUsersListRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( InstituteUsersListRequest $request ) {
		// @TODO :: convert Course name to UTF 8
		$userType = [
			InstituteUsersListViewHelper::SELECT_USER_TYPE_CREATOR => trans('users::institute-users-list.index.user_type_creator'),
			InstituteUsersListViewHelper::SELECT_USER_TYPE_LEARNER => trans('users::institute-users-list.index.user_type_learner'),
			InstituteUsersListViewHelper::SELECT_USER_TYPE_TEACHER => trans('users::institute-users-list.index.user_type_teacher')
		];

		$planStatus = [
			InstituteUsersListViewHelper::SELECT_PLAN_STATUS_LOCKED    => trans('users::institute-users-list.index.plan_status_locked'),
			InstituteUsersListViewHelper::SELECT_PLAN_STATUS_UN_LOCKED => trans('users::institute-users-list.index.plan_status_unlocked'),
		];

		$adminUserId = Session::get('user_id');

		// Check if logged in user has permission to export
		$canExport = PermissionHelpers::canExport($adminUserId);

		if ( $request->has('inst_users_button') ) {
			/** @var UserRepo $userRepo */
			$userRepo = App::make(UserRepo::class);

			// If user clicks export button following will be executed
			if ( $canExport && $request->get('inst_users_button') == self::EXPORT ) {

				DBLog::save(LOG_MODULE_INST_LIST, null, 'export', $request->getRequestUri(), Session::get('user_id'),
					$request->all());

				$allUsers = $userRepo->getInstituteUsers();

				$columnNames = [
					'user_name'          => 'Name',
					'user_login'         => 'User Name',
					'user_mobile'        => 'Mobile No.',
					'user_email'         => 'Email Id',
					'course_name'        => 'Courses',
					'user_mod_role_name' => 'User Role',
					'mobile_user'        => 'Mobile User',
					'verified_user'      => 'Verified User',
					'verification_date'  => 'Verified on',
					'user_term_dt'       => 'Signup on'
				];

				GeneralHelpers::exportToExcel($columnNames, $allUsers->all(), 'institute-users-list');
			} // If user clicks user export button following will be executed
			elseif ( $canExport && $request->get('inst_users_button') == self::USER_EXPORT ) {

				DBLog::save(LOG_MODULE_INST_LIST, null, 'user_export', $request->getRequestUri(),
					Session::get('user_id'), $request->all());

				$allUsers = $userRepo->getInstituteUsers();

				$columnNames = [
					'user_firstname' => 'FirstName',
					'user_lastname'  => 'LastName',
					'user_login'     => 'Mobile/Email',
				];

				GeneralHelpers::exportToExcel($columnNames, $allUsers->all(), 'institute-users-list');
			}

			DBLog::save(LOG_MODULE_INST_LIST, null, 'search', $request->getRequestUri(), Session::get('user_id'),
				$request->all());

			/** @var UserMasterRepo $userMasterRepo */
			$userMasterRepo = App::make(UserMasterRepo::class);
			$instituteName = $userMasterRepo->getInstituteByOwnerId($request->get('inst_id'))['user_school_name'];

			// Check if logged in user has permission to view user contact
			$canViewContact = PermissionHelpers::canViewContact($adminUserId);

			/* @var CourseRepo $courseRepo */
			$courseRepo = App::make(CourseRepo::class);

			$courseCodes = $courseRepo->getCourseCodesOfInstitute(GeneralHelpers::clearParam($request->get('inst_id'),
				PARAM_RAW_TRIMMED));

			/**
			 * List of the user
			 * @var LengthAwarePaginator $users
			 */
			$users = $userRepo->getInstituteUsers(true)->appends($request->except('page'));

			/** @var CourseSubscriptionRepo $courseSubscriptionRepo */
			$courseSubscriptionRepo = App::make(CourseSubscriptionRepo::class);
			$learnerCount = $courseSubscriptionRepo->getInstituteUserCount(GeneralHelpers::clearParam($request->get('inst_id'),
				PARAM_RAW_TRIMMED), [CourseRepo::USER_COURSE_ROLE_LEARNER]);

			for ( $index = 0 ; $index < count($users->items()) ; $index++ ) {
				$user = $users->offsetGet($index);
				$user['user_plan'] = false;
				$user['show_change_email'] = $user['show_change_mobile'] = true;

				// Encode the user id
				$user['encoded_id'] = GeneralHelpers::encode($user['user_id']);

				$enrollmentCount = $courseRepo->getEnrollmentCount($user['user_id'],
					[CourseRepo::USER_COURSE_ROLE_LEARNER]);

				// If fetched user is enrolled in any course then don't allow to change the password from backoffice.
				if ( ! (! $enrollmentCount && session('user_id') != BACKOFFICE_ADMIN_ID) ) {
					$user['reset_pwd_url'] = true;
				}

				// If mobile is verified, don't show change mobile button
				if ( $user['user_mobile_verified'] == 1 ) {
					$user['show_change_mobile'] = false;
				} else {
					if ( ! $canViewContact ) {
						$user['show_change_mobile'] = false;
					}
				}

				// If email is verified, don't show change mobile button
				if ( $user['user_email_verified'] == 1 ) {
					$user['show_change_email'] = false;
				} else {
					if ( ! $canViewContact ) {
						$user['show_change_email'] = false;
					}
				}

				$users->offsetSet($index, $user);
			}
		}

		return view('users::institute-users-list',
			compact('canExport', 'planStatus', 'userType', 'users', 'instituteName', 'courseCodes', 'learnerCount'));
	}

}
