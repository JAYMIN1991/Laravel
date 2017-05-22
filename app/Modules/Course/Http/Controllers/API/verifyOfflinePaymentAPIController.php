<?php

namespace App\Modules\Course\Http\Controllers\API;

use App\Common\GeneralHelpers;
use App\Common\URLHelpers;
use App\Modules\Course\Repositories\Contracts\CourseVerifyOfflinePaymentRepo;
use Illuminate\Http\Request;
use DBLog;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Controller;
use Log;

class verifyOfflinePaymentAPIController extends Controller {

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param                          $id
	 * @param                          $userId
	 * @param                          $toDO
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function generateOfflinePaymentInvoice( Request $request, $id, $userId, $toDO ) {
		$doAction = URLHelpers::decodeGetParam($toDO);
		$offlinePaymentId = $id;
		$isSend = 1;
		$backOfficeLoggedUser = URLHelpers::decodeGetParam($userId);

		try {
			// prepare payload
			$payload = array(
				'offline_payment_id' => $offlinePaymentId,
				'is_send'            => $isSend,
				'backoff_user'       => $backOfficeLoggedUser,
				'do_action'          => $doAction
			);
			$result = [];
			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI('checkout/invoice/offline/generate', 'POST', $payload);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody());

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response->status == 1 && $response->data->generated == 1 ) {
					$result['status'] = 1;

					// make log to track time and user
					DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $offlinePaymentId, $doAction, $request->getRequestUri(), $backOfficeLoggedUser, $payload);
				}

				return $this->sendResponse($result, 200);
			}

		} catch ( ClientException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());

			return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()), 400);
		}
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 */
	public function markReturnOrCancel( Request $request){

		$offlinePaymentId = $request->get('offline_payment_id');
		$returnCancel = $request->get('return_cancel');
		$reason = $request->get('reason');

		if($returnCancel == OFFLINE_PAY_STATUS_CANCELLED){
			$returnCancel = OFFLINE_PAY_STATUS_CANCELLED;
		} else {
			$returnCancel = OFFLINE_PAY_STATUS_INSTRUMENT_INVALID;
		}

		/** @var  CourseVerifyOfflinePaymentRepo $verifyOfflinePaymentRepo */
		$verifyOfflinePaymentRepo = \App::make(CourseVerifyOfflinePaymentRepo::class);

		return $verifyOfflinePaymentRepo->verifyOfflinePaymentReturnCancel( $offlinePaymentId, $returnCancel, $reason );
	}
}
