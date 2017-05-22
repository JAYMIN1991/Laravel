<?php

namespace App\Modules\Users\Http\Controllers\API;

use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Requests\InstituteUsersList\API\ChangeEmailRequest;
use App\Modules\Users\Http\Requests\InstituteUsersList\API\ChangeMobileRequest;
use DBLog;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp;
use Log;


/**
 * Class InstituteUsersListAPIController
 * @package App\Modules\Users\Http\Controllers
 */
class InstituteUsersListAPIController extends Controller {

	/**
	 * Change email id of public user
	 *
	 * @param \App\Modules\Users\Http\Requests\InstituteUsersList\API\ChangeEmailRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function changeEmail( ChangeEmailRequest $request ) {
		$decryptedId = $request->get('decryptedId'); // Get the decrypted Id from the request
		$result = [];
		// Parameters required by the public API
		$parameters = [
			'user_id'      => $decryptedId,
			'email_id'     => $request->get('email'),
			'backoff_user' => $request->get('auth_user')['user_id'],
		];

		DBLog::save(LOG_MODULE_INST_LIST, $decryptedId, 'change_email', $request->getRequestUri(),
			\Request::get('auth_user')['user_id'], $parameters);

		try {
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI('account/profile/email/reset', 'POST', $parameters);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody());

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response->status == 1 && $response->data->changed == 1 ) {
					$result['status'] = 1;
				}

				return $this->sendResponse($result, 200);
			}
		} catch ( ClientException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());

			return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()), 400);
		}

		// Default error response
		return $this->sendResponse(['message' => trans('shared::message.error.something_wrong'), 'status' => 0], 400);
	}

	/**
	 * Change mobile number of public user
	 *
	 * @param \App\Modules\Users\Http\Requests\InstituteUsersList\API\ChangeMobileRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function changeMobile( ChangeMobileRequest $request ) {
		$decryptedId = $request->get('decryptedId'); // Get the decrypted Id from the request
		$result = [];
		// Parameters required by the public API
		$parameters = [
			'user_id'      => $decryptedId,
			'mobile_no'    => $request->get('mobile'),
			'backoff_user' => $request->get('auth_user')['user_id'],
		];

		DBLog::save(LOG_MODULE_INST_LIST, $decryptedId, 'change_mobile', $request->getRequestUri(),
			\Request::get('auth_user')['user_id'], $parameters);

		try {
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI('account/profile/mobile_no/reset', 'POST', $parameters);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody());

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response->status == 1 && $response->data->changed == 1 ) {
					$result['status'] = 1;
				}

				return $this->sendResponse($result, 200);
			}
		} catch ( ClientException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());

			return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()), 400);
		}

		// Default error response
		return $this->sendResponse(['message' => trans('shared::message.error.something_wrong'), 'status' => 0], 400);
	}
}
