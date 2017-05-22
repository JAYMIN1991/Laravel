<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/2/17
 * Time: 3:24 PM
 */

namespace App\Modules\Subscription\Events\Listeners;

use App\Common\GeneralHelpers;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;

/**
 * Class JoinCourse
 * @package App\Modules\Subscription\Events
 */
class JoinCourse {

	/**
	 * Handle the join course event
	 *
	 * @param int  $userId         Id of the user
	 * @param int  $courseId       Id of course
	 * @param int  $backOfficeUser Id of the back-office user
	 * @param bool $isPublic       True will consider course as public, otherwise private
	 * @param bool $notify         True will notify course owner, otherwise false
	 *
	 * @return array
	 */
	public function handle( $userId, $courseId, $backOfficeUser, $isPublic = true, $notify = true ) {
		// prepare payload
		$result['status'] = 0;
		$payload = [
			'user_id'      => $userId,
			'course_id'    => $courseId,
			'notify_owner'  => $notify,
			'backoff_user' => $backOfficeUser,
		];

		$url = 'course/copy/learner/to/';

		$url .= ( $isPublic ) ? 'public/' : 'private/';

		try {
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI($url, 'GET', $payload);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response['status'] == 1 ) {
					$result['status'] = 1;
					$result['data'] = [
						$userId => (array_key_exists('data', $response) ? ['joined' => $response['data']['joined']] : [])
					];

					return $result;
				}
			} else {
				$result['data'] = [$userId => ['code' => 0, 'message' => trans('exception.unknown_error.message')]];

				return $result;
			}
		} catch ( ClientException $e ) {

			$response = GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
			$result['data'] = [$userId => $response['message']];

			return $result;
		}
		$result['data'] = [$userId => ['code' => 0, 'message' => trans('exception.unknown_error.message')]];

		return $result;
	}

}