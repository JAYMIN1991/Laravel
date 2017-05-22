<?php
namespace App\Modules\Content\Events\Listeners;

use App\Common\GeneralHelpers;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;

/**
 * Class ReviewCourse
 * @package App\Modules\Subscription\Events
 */
class ReviewCourse {

	/**
	 * @var array $result Contains result of the API call
	 */
	private $result;

	/**
	 * Handle the review course event
	 *
	 * @param int    $courseId Id of the course
	 * @param int    $userId   Id of the backoffice user
	 * @param int    $status   New status of the course
	 * @param string $remarks  Comment for the course status change
	 *
	 * @return array
	 */
	public function handle( $courseId, $userId, $status, $remarks ) {

		/* Initialize status of result */
		$this->result['status'] = 0;

		/* Prepare payload */
		$payload = [
			'backoff_user' => $userId,
			'course_id'    => $courseId,
			'status'       => $status,
			'remarks'      => $remarks,
		];

		try {
			/* Calling Public API */
			$responseAPI = GeneralHelpers::callAPI('course/review/', 'POST', $payload);

			/* Check API call status */
			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);

				if ( $response['status'] == 1 ) {

					/* Got status = 1, means operation is successful */
					$this->result['status'] = 1;
					$this->result['data']['statusChange'] = $response['data']['changed'] == 1 ? true : false;
					$this->result['data']['oldStatus'] = $response['data']['old'];
					$this->result['data']['newStatus'] = $response['data']['new'];

					return $this->result;
				} else {
					/* http status code is 200, but transaction status is zero */
					return $this->sendFailedResponse(trans('exception.unknown_error.message'));
				}
			} else {
				/* Http status is other than 200 */
				return $this->sendFailedResponse(trans('exception.unknown_error.message'));
			}

		} catch ( ClientException $e ) {
			$response = GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
			if(array_key_exists('errors',$response)){
				$this->result['errors'] = $response['errors'];
			}
			return $this->sendFailedResponse($response['message']);
		}

	}

	/**
	 * This function will send failed response in case of getting http status other than 200
	 * or status variable not equals to 1
	 *
	 * @param string $message message for the result
	 *
	 * @return array Returns array containing result of failed API call
	 */
	private function sendFailedResponse( $message ) {
		$this->result['status'] = 0;
		$this->result['message'] = $message;

		return $this->result;
	}
}