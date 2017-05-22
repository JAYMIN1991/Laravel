<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 22/12/16
 * Time: 4:11 PM
 */

namespace App\Modules\Admin\Auth\Traits;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Admin\Repositories\Contracts\AdminUserIPRepo;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo as AdminUser;
use App\Modules\Login\Http\Requests\LoginRequest;
use Auth;
use Cookie;
use Firewall;
use Helper;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Lang;
use Sentinel;

/**
 * Class AuthenticateBackOfficeUsersTrait
 * @package App\Modules\Admin\Auth\Traits
 */
trait AuthenticateBackOfficeUsersTrait {

	use RedirectsUsers, ThrottlesLogins;

	/**
	 * @var string
	 */
	protected $redirectTo = "/backoffice/dashboard";

	/**
	 * Returns the view of login
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function showLoginForm() {
		/*
		 * Keep It:  used for manually activating user
		   $user = Sentinel::findById(4);
		   $activation = Activation::create($user);
		   Activation::complete($user, $activation["code"]);
		*/
		return view("login::login");
	}

	/**
	 * Authenticate user
	 *
	 * @param \App\Modules\Login\Http\Requests\LoginRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function login( LoginRequest $request ) {
		$this->validateLogin($request);
		$credentials = $this->credentials($request);

		if ( ! Sentinel::authenticate($credentials) ) {

			return $this->sendFailedLoginResponse($request);
		}

		$authUser = Sentinel::getUser();


		//Store User Info in session
		$user = App::make(AdminUser::class);
		$userInfo = $user->getUserForSessionInitialise($authUser->user_id);

		//check if ip restriction is set and request is web
		$userIp = Helper::getIPAddress(true);

		if ( $userInfo['restrict_by_ip'] == 1 && $userIp != false ) {
			$connAllowed = true;

			/* @var AdminUserIPRepo $adminUserIPRepository */
			$adminUserIPRepository = app(AdminUserIPRepo::class);
			$whiteList = $adminUserIPRepository->getAllowedIP($userInfo['user_id']);

			if ( $whiteList ) {
				//whitelist is set, lets check on firewall
				$connAllowed = Firewall::addList($whiteList, 'local', true)
				                       ->setIpAddress(Helper::getIPAddress(true))
				                       ->handle();
			}

			// User needs to be blocked
			if ( ! $connAllowed ) {
				//dd("block him by firewall");
				$this->guard()->logout();
				Sentinel::logout();

				return $this->sendBlockedByFirewallResponse($request);
			}

		}

		session($userInfo);

		return $this->sendLoginResponse($request);
	}

	/**
	 * Validate the user login request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	protected function validateLogin( Request $request ) {
		$this->validate($request, [$this->username() => 'required', 'password' => 'required',]);
	}

	/**
	 * Get the login username to be used by the controller.
	 *
	 * @return string
	 */
	public function username() {
		return 'email';
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	protected function credentials( Request $request ) {
		return ['user_login' => $request->email, 'password' => $request->password];
	}

	/**
	 * Get the failed login response instance.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendFailedLoginResponse( Request $request ) {

		return redirect()
			->back()
			->withInput($request->only($this->username(), 'remember'))
			->withErrors([$this->username() => Lang::get('auth.failed'),]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard() {
		return Auth::guard();
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendBlockedByFirewallResponse( Request $request ) {
		//die("i am redirecting back!");
		return redirect()
			->back()
			->withInput($request->only($this->username(), 'remember'))
			->withErrors([$this->username() => Lang::get('auth.firewall_failed')]);
	}

	/**
	 * Send the response after the user was authenticated.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function sendLoginResponse( Request $request ) {
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		if ( Sentinel::check() ) {
			$c = GeneralHelpers::getJWTToken();
			$jwtCookie = Cookie::make('FA', $c, config('jwt.ttl'), null, null, false, false);

			return redirect()->intended($this->redirectPath())->withCookie($jwtCookie);
		}

		return $this->sendFailedLoginResponse($request);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function logout( Request $request ) {
		$this->guard()->logout();
		Sentinel::logout();
		$request->session()->flush();
		$request->session()->regenerate();

		$forget = Cookie::forget('FA');

		return redirect()->route('login')->withCookie($forget);
	}

	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  mixed                    $user
	 *
	 * @return mixed
	 */
	protected function authenticated( Request $request, $user ) {
		return false;
	}


}