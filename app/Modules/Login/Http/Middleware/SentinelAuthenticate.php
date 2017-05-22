<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/12/16
 * Time: 7:15 PM
 */

namespace App\Modules\Login\Http\Middleware;


use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class SentinelAuthenticate
 * @package App\Modules\Login\Http\Middleware
 */
class SentinelAuthenticate {

	/**
	 * Handle the request
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle( Request $request, Closure $next ) {
		$this->authenticate();

		return $next($request);
	}

	/**
	 * Authenticate the user
	 *
	 * @throws \Illuminate\Auth\AuthenticationException
	 */
	protected function authenticate() {
		if ( Sentinel::check() ) {
			return;
		}

		throw new AuthenticationException('Unauthenticated.');
	}
}