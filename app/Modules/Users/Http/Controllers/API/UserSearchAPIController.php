<?php

namespace App\Modules\Users\Http\Controllers\API;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Users\Http\Requests\UserSearch\API\AddRemarksRequest;
use App\Modules\Users\Http\Requests\UserSearch\API\PasswordResetRequest;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use DBLog;
use Exception;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use Helper;
use Log;

/**
 * Class UserSearchAPIController
 * @package App\Modules\Users\Http\Controllers\API
 */
class UserSearchAPIController extends Controller {

	/**
	 * API to reset the public user password from user search page of backoffice.
	 * This will ultimately call the API of public site to change the password.
	 *
	 * @param \App\Modules\Users\Http\Requests\UserSearch\API\PasswordResetRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function passwordReset( PasswordResetRequest $request ) {
		$decryptedId = $request->get('decryptedId'); // Get the decrypted Id from the request
		$result = [];
		// Parameters required by the public API
		$parameters = [
			'user_id'      => $decryptedId,
			'new_password' => $request->get('pwd'),
			'backoff_user' => $request->get('auth_user')['user_id'],
		];

		DBLog::save(LOG_MODULE_USER_SEARCH, $decryptedId, 'reset_password', $request->getRequestUri(),
			\Request::get('auth_user')['user_id'], $parameters);

		try {
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI('account/password/reset', 'POST', $parameters);

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

			return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()),
				$e->getResponse()->getStatusCode());
		}

		// Default error response
		return $this->sendResponse(['message' => trans('shared::message.error.something_wrong'), 'status' => 0], 400);
	}

	/**
	 * Add the remarks on public user
	 *
	 * @param \App\Modules\Users\Http\Requests\UserSearch\API\AddRemarksRequest $request
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function addRemarks( AddRemarksRequest $request ) {
		$userId = $request->get('decryptedId');
		$remarks = $request->get('remark');
		$result = [];

		$userMasterRepo = App::make(UserMasterRepo::class);

		DBLog::save(LOG_MODULE_USER_SEARCH, $userId, 'reset_password', $request->getRequestUri(),
			\Request::get('auth_user')['user_id'], ['remarks' => $remarks]);

		try {
			$userMasterRepo->userExists($userId);
		} catch ( Exception $e ) {
			GeneralHelpers::logExceptionAndThrow($e, ['user_id' => $userId]);
		}

		$remarkData = [];
		$remarkData["remark_text"] = $remarks;
		$remarkData["remark_user_id"] = $userId;
		$remarkData["remark_user_inst_id"] = 0;//@todo: Find institution id from user id
		$remarkData["bkoff_user_id"] = $request->get('auth_user')['user_id'];
		$remarkData["remark_ip"] = $request->ip();
		$remarkData["remark_dt"] = Helper::datetimeToTimestamp();
		$remarkData["device_type"] = "BACKOFFICE";
		$userRepo = App::make(UserRepo::class);
		$id = $userRepo->insertUserRemarks($remarkData);

		if ( $id ) {
			$result['status'] = 1;
		}


		return $this->sendResponse($result, 200);
	}
}
