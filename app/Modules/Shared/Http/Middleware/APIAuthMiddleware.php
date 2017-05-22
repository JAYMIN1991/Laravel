<?php

namespace App\Modules\Shared\Http\Middleware;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use Closure;
use Crypt;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

/**
 * Class APIAuthMiddleware
 * @package App\Modules\Shared\Http\Middleware
 */
class APIAuthMiddleware extends BaseMiddleware {

	/**
	 * @var string
	 */
	protected $prefix = 'Bearer';

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
	 */
	public function handle( $request, Closure $next ) {

		try {
			$this->decryptAuthorizationHeader($request);
		} catch ( TokenInvalidException $e ) {

			GeneralHelpers::logException($e);

			if ( $request->wantsJson() ) {
				$errorMessage = [
					'code'    => 1,
					'message' => trans('shared::message.error.invalid_auth_header'),
				];

				if ( strtolower(App::environment()) == APP_ENV_LOCAL && ! GeneralHelpers::isNull($e) ) {
					$errorMessage['file'] = $e->getFile();
					$errorMessage['line'] = $e->getLine();
					$errorMessage['trace'] = $e->getTrace();
				}

				return response()->json([
					'status'  => 0,
					'message' => trans('shared::message.error.invalid_request'),
					'errors'  => [$errorMessage]
				]);
			} else {
				throw $e;
			}
		}

		if ( $this->auth->parser()->setRequest($request)->hasToken() ) {

			try {
				$this->auth->parseToken()->authenticate();
				$id = $this->auth->getPayload()->get('sub');
				$userDetail = App::make(AdminUsersRepo::class)->getUserForSessionInitialise($id);

				if ( ! empty($userDetail) ) {
					$request->merge(['auth_user' => $userDetail]);
				}

			} catch ( Exception $e ) {
				GeneralHelpers::logExceptionAndThrow($e);
			}
		}

		return $next($request);
	}

	/**
	 * Decrypt the authorization header
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return void
	 * @throws \Tymon\JWTAuth\Exceptions\TokenInvalidException
	 */
	private function decryptAuthorizationHeader( Request $request ) {
		if ( $request->hasHeader('Authorization') ) {
			$auth = $request->headers->get('Authorization', null);

			if ( $auth == '' || $auth == null ) {
				throw new TokenInvalidException(trans('exception.invalid_token.message'), trans('exception.invalid_token.code'));
			}

			$authHeader = $this->removePrefixFromHeader($auth);
			$token = Crypt::decrypt($authHeader);
			$request->headers->set('Authorization', $this->prefix . ' ' . $token);
		} else {
			throw new TokenInvalidException();
		}
	}

	/**
	 * Remove the prefix from the Authorization header
	 *
	 * @param string $header Authorization header
	 *
	 * @return null|string Trimmed header
	 */
	private function removePrefixFromHeader( $header ) {
		if ( $header && stripos($header, $this->prefix) === 0 ) {
			return trim(str_ireplace($this->prefix, '', $header));
		}

		return null;
	}
}
