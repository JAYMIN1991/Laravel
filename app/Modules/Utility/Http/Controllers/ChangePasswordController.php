<?php

namespace App\Modules\Utility\Http\Controllers;

use App;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use App\Modules\Utility\Http\Requests\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use DBLog;
use Redirect;
use Session;
use View;

/**
 * Class ChangePasswordController
 * @package App\Modules\Utility\Http\Controllers
 */
class ChangePasswordController extends Controller {

	/**
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index() {
		return View::make('utility::changePassword.index');
	}

	/**
	 * @param \App\Modules\Utility\Http\Requests\ChangePasswordRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function updatePassword( ChangePasswordRequest $request ) {

		// Variable set
		$newPassword = $request->new_password;

		// Repository action invoke
		$changePasswordRepo = App::make(AdminUsersRepo::class)->changePassword($newPassword);

		// If change password is successful, then log entry and redirect user
		if ( $changePasswordRepo ) {

			// Log ChangePassword event
			DBLog::save(LOG_MODULE_USERS, Session::get('user_id'), 'changePassword', $request->getRequestUri(), Session::get('user_id'), $request->all());

			// Redirect
			return Redirect::route('utility.changePassword.index')
			               ->with('message', trans('shared::message.success.process'));
		} else {

			// Redirect
			return Redirect::route('utility.changePassword.index');
		}
	}
}
