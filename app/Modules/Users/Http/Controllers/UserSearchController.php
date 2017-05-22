<?php

namespace App\Modules\Users\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Users\Http\Requests\UserSearch\UserSearchRequest;
use App\Modules\Users\Repositories\Criteria\UserSearchCrit;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use DBLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

/**
 * Class UserSearchController
 * @package App\Modules\Users\Http\Controllers
 */
class UserSearchController extends Controller {

	/**
	 * Get the index view of user search page.
	 *
	 * @param \App\Modules\Users\Http\Requests\UserSearch\UserSearchRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( UserSearchRequest $request ) {

		$attributes = [
			'user_mobile',
			'user_login',
			'user_school_name',
			'user_acc_verified',
			'user_acc_auth_mode',
			'user_acc_closed'
		];

		/* @var UserMasterRepo $userMasterRepo */
		$userMasterRepo = App::make(UserMasterRepo::class);
		$userMasterRepo->pushCriteria(App::make(UserSearchCrit::class));

		/** @var LengthAwarePaginator $users */
		$users = $userMasterRepo->getUsers($attributes, [])->appends($request->except('page'));

		/* @var CourseRepo $courseRepo */
		$courseRepo = App::make(CourseRepo::class);

		/** @var UserRepo $userRepo */
		$userRepo = App::make(UserRepo::class);

		for ( $index = 0 ; $index < count($users->items()) ; $index++ ) {
			$user = $users->offsetGet($index);

			$encodedId = GeneralHelpers::encode($user['user_id']);

			if ( empty($user['user_school_name']) ) {
				$user['user_school_name'] = $userRepo->getInstituteNameFromUserCoursesTable($user['user_id'])['user_school_name'];
			}

			$enrollmentCount = $courseRepo->getEnrollmentCount($user['user_id'],
				[CourseRepo::USER_COURSE_ROLE_CREATOR]);

			if ( ! ((! $enrollmentCount || $user['user_acc_closed'] == 1) && session('user_id') != BACKOFFICE_ADMIN_ID) ) {
				$user['reset_pwd_url'] = true;
			}

			$user['add_remark_url'] = route('users.remarks.add', ['id' => $encodedId]);
			$user['course_name'] = $courseRepo->getUserEnrolledCourses($user['user_id']);
			$user['encoded_id'] = $encodedId;
			$users->offsetSet($index, $user);
		}

		$options = [
			ViewHelper::SELECT_OPTION_VALUE_ANY => trans('shared::common.dropdown.any'),
			ViewHelper::SELECT_OPTION_VALUE_NO  => trans('shared::common.dropdown.no'),
			ViewHelper::SELECT_OPTION_VALUE_YES => trans('shared::common.dropdown.yes'),
		];

		DBLog::save(LOG_MODULE_USER_SEARCH, null, 'search', $request->getRequestUri(), Session::get('user_id'),
			$request->all());

		return view('users::user-search', compact('users', 'options'));
	}
}
