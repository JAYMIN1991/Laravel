<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/3/17
 * Time: 4:57 PM
 */

namespace App\Modules\Subscription\Events\Listeners;

use App\Common\GeneralHelpers;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;

/**
 * Class SendInvitation
 * @package App\Modules\Subscription\Events\Listeners
 */
class SendInvitation {

	/**
	 * @param $userId
	 * @param $courseId
	 * @param $inviteRole
	 * @param $inviteUserId
	 * @param $backOfficeUserId
	 *
	 * @return mixed
	 */
	public function handle( $userId, $courseId, $inviteRole, $inviteUserId, $backOfficeUserId ) {

		// prepare payload
		$payload = [
			'user_id'        => $userId,
			'course_id'      => $courseId,
			'invite_role'    => $inviteRole,
			'invite_user_id' => $inviteUserId,
			'backoff_user'   => $backOfficeUserId
		];


		try {
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI('course/invitation/send/', 'POST', $payload);

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
					if ( array_key_exists('errors', $response) ) {
						$result['errors'] = [
							'code'    => $response['errors'][0]['code'],
							'message' => $response['errors'][0]['message'],
						];

						return $result;
					}
				}
			} else {
				$result['data'] = ['code' => 0, 'message' => trans('exception.unknown_error.message')];

				return $result;
			}
		} catch ( ClientException $e ) {
			$response = GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
			$result['message'] = $response['message'];

			if ( array_key_exists('errors', $response) ) {
				$result['errors'] = [
					'code'    => $response['errors'][0]['code'],
					'message' => $response['errors'][0]['message'],
				];

				return $result;
			}

			return $result;
		}
		$result['data'] = ['code' => 0, 'message' => trans('exception.unknown_error.message')];

		return $result;
	}
}