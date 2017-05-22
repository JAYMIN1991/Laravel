<?php

namespace App\Modules\Course\Events\Listeners;

use App\Common\GeneralHelpers;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use Session;

/**
 * Class CouponGenerate
 * @package App\Modules\Subscription\Events
 */
class CouponGenerate {

	/**
	 * @var array $result Contains result of the API call
	 */
	private $result;


	/**
	 * @param $offlinePaymentId
	 * @param $instituteId
	 * @param $courseId
	 * @param $totalBuyer
	 *
	 * @return array
	 */
	public function handle( $offlinePaymentId, $instituteId, $courseId, $totalBuyer ) {

		/* Initialize status of result */
		$this->result['status'] = 0;

		/* Prepare payload */
		$payload = array(
			'backoff_user'       => Session::get('user_id'),
			'offline_payment_id' => $offlinePaymentId,
			'institute_id'       => $instituteId,
			'course_id'          => $courseId,
			'total_buyer'        => $totalBuyer
		);

		try {
			/* Calling Public API */
			$responseAPI = GeneralHelpers::callAPI('course/offline/payment/coupon/generate', 'POST', $payload);

			/* Check API call status */
			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);

				if ( $response['status'] == 1 ) {

					/* Got status = 1, means operation is successful */
					$this->result['status'] = 1;
					$this->result['data']['tranStatus'] = true;

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
			if ( array_key_exists('errors', $response) ) {
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