<?php

namespace App\Modules\Users\Http\Controllers;


use App;
use App\Http\Controllers\Controller;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use DBLog;
use Illuminate\Http\Request;
use Session;

/**
 * Class AccountVerificationController
 * @package App\Modules\Users\Http\Controllers
 */
class AccountVerificationController extends Controller {

	/**
	 * Get the name, email Id and verification code/link of unverified users
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function unverifiedAccountsList( Request $request ) {
		$page = 1;
		$loginId = null;

		if ( $request->has('page') ) {
			$page = $request->get('page');
		}
		if ( $request->has('loginId') ) {
			$loginId = trim($request->get('loginId'));
		}
		if ( $request->has("isSearch") ) {
			$page = 1;
		}

		/* @var UserRepo $user */
		$user = App::make(UserRepo::class);

		/* @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator */
		$unverifiedUsers = $user->getUnverifiedUsers($loginId, $page);

		DBLog::save(LOG_MODULE_ACC_PENDING_VERIFICATION, null, 'search', $request->getRequestUri(), Session::get('user_id'),
			$request->all());

		return view("users::account-verification", compact('unverifiedUsers'));
	}

}
