<?php

namespace App\Modules\Account\Http\Controllers\API;

use App\Common\GeneralHelpers;
use App\Common\URLHelpers;
use App\Modules\Account\Http\Requests\MarkAsPaidRequest;
use App\Http\Controllers\Controller;
use DB;
use DBLog;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Log;
use Session;

/**
 * Class CourseOrdersAPIController
 * Get course order API call method
 * @package App\Modules\Account\Http\Controllers\API
 */
class CourseOrdersAPIController extends Controller {

	/**
	 * Change status for perticular trans_id
	 * @param \App\Modules\Account\Http\Requests\MarkAsPaidRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function markAsPaid( MarkAsPaidRequest $request ) {
		$transId = $request->get('trans_id'); // Get the decrypted Id from the request
		$result = [];
		$buyerInvoiceIsAvailable = DB::table(TABLE_PAY_BUYER_INVOICES)
		                             ->where('trans_id', '=', $transId)
		                             ->count('trans_id');

		$sellerInvoiceIsAvailable = DB::table(TABLE_PAY_SELLER_INVOICES)
		                              ->where('trans_id', '=', $transId)
		                              ->count('trans_id');

		if ( ! empty($buyerInvoiceIsAvailable) && ! empty($sellerInvoiceIsAvailable) ) {

			try {
				$updateBuyerInvoice = DB::table(TABLE_PAY_BUYER_INVOICES)
				                        ->where('trans_id', '=', $transId)
				                        ->update(['is_paid_seller' => 1]);
				$updateSellerInvoice = DB::table(TABLE_PAY_SELLER_INVOICES)
				                         ->where('trans_id', '=', $transId)
				                         ->update(['is_paid_seller' => 1]);

				if ( isset($updateBuyerInvoice) && isset($updateSellerInvoice) ) {
					$result['status'] = 1;

					// make log for user and time
					DBLog::save(LOG_MODULE_COURSE_ORDER_SUMMARY, $transId, 'mark_as_paid', $request->getRequestUri(), Session::get('user_id'), $result);
				}

				return $this->sendResponse($result, 200);
			} catch ( ClientException $e ) {
				Log::error($e->getResponse()->getBody(), $e->getTrace());

				return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()), $e->getResponse()
				                                                                                   ->getStatusCode());
			}
		}

		// Default error response
		return $this->sendResponse(['message' => trans('shared::message.error.something_wrong'), 'status' => 0], 400);
	}

	/**
	 * Generate invoice for buyer and seller
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function generateInvoice( Request $request ) {
		$doAction = URLHelpers::decodeGetParam($request->get('do_action'));
		$transId = URLHelpers::decodeGetParam($request->get('trans_id'));
		$userId = URLHelpers::decodeGetParam($request->get('user_id'));
		$isSend = URLHelpers::decodeGetParam($request->get('is_send'));
		$backOfficeLoggedUser = $request->get('user')['user_id'];

		$apiURL = ($doAction == 'generate_buyer_invoice') ? 'checkout/buyer/invoice' : 'checkout/seller/invoice';

		$result = [];
		// Parameters required by the public API
		try {
			$parameters = [
				'user_id'        => $userId,
				'transaction_id' => $transId,
				'is_send'        => $isSend,
				'backoff_user'   => $backOfficeLoggedUser,
			];

			// Calling Public API
			$responseAPI = GeneralHelpers::callAPI($apiURL, 'POST', $parameters);

			if ( $responseAPI->getStatusCode() == 200 ) {
				$response = GuzzleHttp\json_decode($responseAPI->getBody());

				// If response code is 200, status is 1 and data changed is 1 set the status as 1 i.e, changed
				if ( $response->status == 1 && $response->data->generated == 1 ) {
					$result['status'] = 1;

					// make log to track time and user
					DBLog::save(LOG_MODULE_COURSE_ORDER_SUMMARY, $transId, $doAction, $request->getRequestUri(), $backOfficeLoggedUser, $parameters);
				}

				return $this->sendResponse($result, 200);
			}
		} catch ( ClientException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());

			return $this->sendResponse(GuzzleHttp\json_decode($e->getResponse()->getBody()), 400);
		}
	}
}
