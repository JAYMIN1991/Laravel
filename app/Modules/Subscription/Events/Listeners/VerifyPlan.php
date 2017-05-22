<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 1/3/17
 * Time: 6:09 PM
 */

namespace App\Modules\Subscription\Events\Listeners;

use GuzzleHttp;
use App\Common\GeneralHelpers;
use GuzzleHttp\Exception\ClientException;

/**
 * Class VerifyPlan
 * @package App\Modules\Subscription\Events\Listeners
 */
class VerifyPlan {

	/**
	 * @param $userId
	 * @param $remarks
	 * @param $backOfficeUser
	 *
	 * @return mixed
	 */
	public function handle( $userId, $remarks, $backOfficeUser ) {

		$payload = [
			'user_id'      => GeneralHelpers::clearParam($userId, PARAM_RAW_TRIMMED),
			'backoff_user' => $backOfficeUser
		];

		if (!is_null($remarks)){
			$payload['remarks'] = $remarks;
		}

		try {
			$responseAPI = GeneralHelpers::callAPI('plan/verify/', 'POST', $payload);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response['status'] == 1 ) {
					if ( array_key_exists('data', $response) ) {
						$result['status'] = 1;
						$result['message'] = $response['data'];

						return $result;
					}
				} else {
					if ( array_key_exists('errors', $response['body']) ) {
						$result['data']['errors'] = [
							'code'    => $response['errors']['code'],
							'message' => $response['errors']['message'],
						];

						return $result;
					}
				}
			} else {
				$result['data'] = [$userId => ['code' => 0, 'message' => trans('exception.unknown_error.message')]];

				return $result;
			}
		} catch ( ClientException $exception ) {
			$response = GuzzleHttp\json_decode($exception->getResponse()->getBody(), true);

			return $response;
		}

		$result['data'] = [$userId => ['code' => 0, 'message' => trans('exception.unknown_error.message')]];

		return $result;
	}
}