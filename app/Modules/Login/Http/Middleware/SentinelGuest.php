<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 27/12/16
 * Time: 10:58 AM
 */

namespace App\Modules\Login\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class SentinelGuest
 * @package App\Modules\Login\Http\Middleware
 */
class SentinelGuest
{

	/**
	 * Handle the incoming request. If user is already logged in redirect to dashboard
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 */
	public function handle( Request $request, Closure $next)
	{
		if (!Sentinel::guest())
		{
			return redirect()->route('dashboard');
		}

		return $next($request);
	}
}