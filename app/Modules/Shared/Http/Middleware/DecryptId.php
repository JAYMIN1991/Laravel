<?php

namespace App\Modules\Shared\Http\Middleware;

use App\Common\GeneralHelpers;
use Closure;
use Exception;
use Log;
use Route;

/**
 * Class DecryptId
 * @package App\Modules\Shared\Http\Middleware
 */
class DecryptId {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle( $request, Closure $next ) {

		try {
			if ( $request->id ) {
				$request->merge(['decryptedId' => GeneralHelpers::decode($request->id)]);

				return $next($request);
			}
		} catch ( Exception $e ) {
			Log::error($e->getMessage() . trans('shared::message.error.decrypt_id_invalid_route', [
					'route_name' => Route::getCurrentRoute()
					                     ->getName()
				]), $e->getTrace());
		}


		return $next($request);
	}
}
