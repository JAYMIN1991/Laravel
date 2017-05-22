<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 1/3/17
 * Time: 4:18 PM
 */

namespace App\Modules\Subscription\Events\Listeners;

use App\Common\GeneralHelpers;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;

/**
 * Class CancelPlan
 * @package App\Modules\Subscription\Events\Listeners
 */
class CancelPlan {

	/**
	 * @param      $userId
	 * @param      $backOfficeUser
	 * @param int  $inactive
	 * @param null $remarks
	 *
	 * @return mixed
	 */
	public function handle( $userId, $backOfficeUser, $inactive = 1, $remarks = null ) {
		$payload = [
			'user_id'      => GeneralHelpers::clearParam($userId, PARAM_RAW_TRIMMED),
			'backoff_user' => $backOfficeUser
		];
		$url = null;

		if ( $inactive ) {
			$payload['inactive'] = $inactive;
			$url = 'plan/deactivate/';
		} else {
			$payload['remarks'] = GeneralHelpers::clearParam($remarks, PARAM_RAW_TRIMMED);
			$url = 'plan/cancel/';
		}

		try {
			$responseAPI = GeneralHelpers::callAPI($url, 'POST', $payload);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response['status'] == 1 ) {
					if ( array_key_exists('data', $response) ) {
						$result['status'] = 1;
						$result['data'] = $response['data'];

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