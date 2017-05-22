<?php

namespace App\Modules\Course\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\URLHelpers;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use App\Modules\Course\Repositories\Contracts\CourseVerifyOfflinePaymentRepo;
use App\Modules\Course\Repositories\Criteria\SearchCommonOfflinePaymentCrit;
use App\Modules\Course\Repositories\Criteria\SearchVerifyOfflinePaymentCrit;
use App\Modules\Shared\Misc\CourseVerifyOfflinePaymentCoupon;
use DBLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class verifyOfflinePaymentController extends Controller {

	protected $courseOfflinePaymentRepo;
	protected $courseVerifyOfflinePaymentRepo;


	/**
	 * verifyOfflinePaymentController constructor.
	 *
	 * @param CourseOfflinePaymentRepo       $courseOfflinePaymentRepo
	 * @param CourseVerifyOfflinePaymentRepo $courseOfflinePaymentRepo
	 */
	public function __construct( CourseOfflinePaymentRepo $courseOfflinePaymentRepo,
	                             CourseVerifyOfflinePaymentRepo $courseOfflinePaymentRepo ) {
		$this->courseOfflinePaymentRepo = $courseOfflinePaymentRepo;
		$this->courseVerifyOfflinePaymentRepo = $courseOfflinePaymentRepo;
	}

	/**
	 * Load default settings for verify offline payment listing page
	 * @return \Illuminate\Contracts\View\View
	 *
	 * @param $request
	 */
	public function index( Request $request ) {
		// Generate invoice common URL
		$generateInvoiceURL = HTTP_SERVER_CATALOG . 'app/download/';

		// Collection for coupon code status yes or no
		$couponStatus = array(
			CourseVerifyOfflinePaymentCoupon::COUPON_GENERATED     => trans('course::verify_offline.common.yes'),
			CourseVerifyOfflinePaymentCoupon::COUPON_NOT_GENERATED => trans('course::verify_offline.common.no')
		);

		// Collection for Check status
		$checkStatus = array(
			CourseVerifyOfflinePaymentCoupon::COUPON_GENERATED     => trans('course::verify_offline.common.yes'),
			CourseVerifyOfflinePaymentCoupon::COUPON_NOT_GENERATED => trans('course::verify_offline.common.no')
		);

		// Repo for course Offline payment
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

		$courseVerifyOfflinePaymentRepo = App::make(CourseVerifyOfflinePaymentRepo::class);

		if ( $request->exists('button_search') ) {
			// Apply search criteria as per search filter
			/* Apply coupon status and check status criteria */
			$courseOfflinePaymentRepo->pushCriteria(app(SearchVerifyOfflinePaymentCrit::class));
			/* Apply common search criteria like cheque no. cheque date */
			$courseOfflinePaymentRepo->pushCriteria(app(SearchCommonOfflinePaymentCrit::class));

			$verifyOfflinePaymentRecords = $courseOfflinePaymentRepo->getVerifyOfflinePaymentRecords(true)
			                                                        ->appends($request->except('page'));

			if ( ! $verifyOfflinePaymentRecords->isEmpty() ) {

				foreach ( $verifyOfflinePaymentRecords as $key => $value ) {

					if ( $value['coupon_codes'] ) {
						$value['coupon_codes'] = nl2br($value['coupon_codes']);
						$verifyOfflinePaymentRecords[$key] = $value;
					}
					// Generate Coupon URL
					$value['generate_coupon_url'] = route('course.verify_offline.generate_coupon', [
						'offline_payment_id' => $value['offline_payment_id'],
						'institute_id'       => $value['user_id'],
						'course_id'          => $value['course_id'],
						'total_buyer'        => $value['total_buyer']
					]);

					// Generate Mark as Clear URL
					$value['mark_as_check_cleared_url'] = route('course.verify_offline.mark_as_clear', [
						'id'                      => $value['offline_payment_id'],
						'instrumentProcessStatus' => $value['is_instrument_processed']
					]);

					// Download Buyer invoice
					if ( $value['offline_buyer_invoice_filename'] ) {
						$value['download_buyer_invoice_url'] = $generateInvoiceURL . '?file=' . URLHelpers::encodeGetParam(DIR_WS_RESOURCES_BUYER_OFFLINE_INVOICES . $value['offline_buyer_invoice_filename']) . '&dsrc=' . URLHelpers::encodeGetParam('backoffice') . '&tgt=' . URLHelpers::encodeGetParam('s3') . '&m=' . URLHelpers::encodeGetParam('offline_buyer_invoice') . '&u=' . URLHelpers::encodeGetParam(Session::get('user_id'));
						$verifyOfflinePaymentRecords[$key] = $value;
					} else {
						$value['generate_buyer_invoice_url'] = route('api.account.generate-invoice', [
							'do_action' => URLHelpers::encodeGetParam('generate_buyer_invoice'),
							'trans_id'  => URLHelpers::encodeGetParam($value['offline_payment_id']),
							'user_id'   => URLHelpers::encodeGetParam($value['course_owner']),
							'is_send'   => URLHelpers::encodeGetParam('1')
						]);
						$verifyOfflinePaymentRecords[$key] = $value;
					}

					// Download Seller invoice
					if ( ! empty($value['offline_seller_invoice_filename']) ) {
						$value['download_seller_invoice_url'] = $generateInvoiceURL . '?file=' . URLHelpers::encodeGetParam(DIR_WS_RESOURCES_SELLER_OFFLINE_INVOICES . $value['offline_seller_invoice_filename']) . '&dsrc=' . URLHelpers::encodeGetParam('backoffice') . '&tgt=' . URLHelpers::encodeGetParam('s3') . '&m=' . URLHelpers::encodeGetParam('offline_seller_invoice') . '&u=' . URLHelpers::encodeGetParam(Session::get('user_id'));
						$verifyOfflinePaymentRecords[$key] = $value;
					} else {
						$value['generate_seller_invoice_url'] = route('api.account.generate-invoice', [
							'do_action' => URLHelpers::encodeGetParam('generate_seller_invoice'),
							'trans_id'  => URLHelpers::encodeGetParam($value['offline_payment_id']),
							'user_id'   => URLHelpers::encodeGetParam($value['course_owner']),
							'is_send'   => URLHelpers::encodeGetParam('1')
						]);
						$verifyOfflinePaymentRecords[$key] = $value;
					}
					/* generate offline invoices url */
					$value['generate_offline_invoices_url'] = route('api.course.generate_offline_invoice', [
						'id'      => URLHelpers::encodeGetParam($value['offline_payment_id']),
						'user_id' => URLHelpers::encodeGetParam(Session::get('user_id')),
						'to_do'   => URLHelpers::encodeGetParam('invoice_offline_generate')
					]);
					$value['user_id'] = URLHelpers::encodeGetParam(Session::get('user_id'));
					$value['to_do'] = URLHelpers::encodeGetParam('invoice_offline_generate');

					$verifyOfflinePaymentRecords[$key] = $value;
					$proceedVerifyOfflinePayment = $courseVerifyOfflinePaymentRepo->canProceedVerifyOfflinePayment($value['offline_payment_id']);

					if ( ! $proceedVerifyOfflinePayment ) {
						$value['mark_check_cleared_block'] = '0';
						$value['generate_coupon_block'] = '0';
						$value['mark_return_or_cancel_block'] = '0';
						$verifyOfflinePaymentRecords[$key] = $value;
					} else {

						if ( ! empty($value['is_instrument_processed']) ) {
							$value['mark_check_cleared_block'] = '0';

							if ( ! empty($value['is_coupon_generated']) ) {
								$value['generate_coupon_block'] = '0';
							} else {
								$value['generate_coupon_block'] = '1';
							}

							if ( empty($value['offline_seller_invoice_filename']) && empty($value['offline_buyer_invoice_filename']) ) {
								$value['generate_offline_invoices_block'] = '1';
							} else {
								$value['generate_offline_invoices_block'] = '0';
								if ( ! empty($value['offline_buyer_invoice_filename']) ) {
									$value['download_buyer_invoice_block'] = '1';
									$value['generate_buyer_invoice_block'] = '0';
								} else {
									$value['download_buyer_invoice_block'] = '0';
									$value['generate_buyer_invoice_block'] = '1';
								}

								if ( ! empty($value['offline_seller_invoice_filename']) ) {
									$value['download_seller_invoice_block'] = '1';
									$value['generate_seller_invoice_block'] = '0';
								} else {
									$value['download_seller_invoice_block'] = '0';
									$value['generate_seller_invoice_block'] = '1';
								}
							}
							$verifyOfflinePaymentRecords[$key] = $value;
						} else {
							$value['mark_check_cleared_block'] = '1';
							$verifyOfflinePaymentRecords[$key] = $value;
						}

						$couponUsageDetails = array();
						$couponUsageDetails = $courseVerifyOfflinePaymentRepo->verifyOfflineGetCouponUsage($value['offline_payment_id']);

						/* if coupon generated and coupon not used till now then show mark_return_or_cancel link */
						if ( ! in_array('1', $couponUsageDetails) ) {
							$value['mark_return_or_cancel_block'] = '1';
							$verifyOfflinePaymentRecords[$key] = $value;
						} else {
							$value['mark_return_or_cancel_block'] = '0';
							$verifyOfflinePaymentRecords[$key] = $value;
						}

					}
				}
			}
		}

		if ( $request->exists('button_export') ) {

			$offlinePaymentRecords = $courseOfflinePaymentRepo->getVerifyOfflinePaymentRecords(false);
			// Column list which are export
			$exportColumnNames = [
				'user_school_name'       => 'Institute',
				'course_name'            => 'Course',
				'report_coupon_codes'    => 'Coupon Codes',
				'total_buyer'            => 'Total Buyers',
				'amount_paid'            => 'Amount Paid',
				'instrument_no'          => 'Cheque No',
				'instrument_date'        => 'Cheque Date',
				'instrument_issuer_name' => 'Bank Name',
				'instrument_issuer_sub'  => 'Branch Name',
				'remarks'                => 'Remarks'
			];

			// DB log for export event
			DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, '', 'export', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentRecords->all());
			// Export to excel
			GeneralHelpers::exportToExcel($exportColumnNames, $offlinePaymentRecords->all(), EXPORT_VERIFY_OFFLINE);
		}

		return \View::make('course::verify_offline.index', compact('couponStatus', 'checkStatus', 'verifyOfflinePaymentRecords'));
	}


	/**
	 * @param \Illuminate\Http\Request $request
	 * @param                          $id
	 * @param                          $instrumentProcessStatus
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function markAsClear( Request $request, $id, $instrumentProcessStatus ) {
		$tranStatus = false;

		$courseVerifyOfflinePaymentRepo = App::make(CourseVerifyOfflinePaymentRepo::class);
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);
		$offlinePaymentData = $courseOfflinePaymentRepo->getOfflinePaymentDetails($id);

		if ( empty($offlinePaymentData['is_instrument_processed']) ) {
			$tranStatus = $courseVerifyOfflinePaymentRepo->verifyOfflineCheckCleared($id, 1);
			// make log for mark as clear status
			DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $id, 'mark_as_clear', $request->getRequestUri(), Session::get('user_id'), $tranStatus);

			return redirect()
				->route('course.verify_offline.index')
				->with('message', trans('shared::message.success.process'));
		} else {
			$tranStatus = false;
			// make log for mark as clear status
			DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $id, 'mark_as_clear', $request->getRequestUri(), Session::get('user_id'), $tranStatus);

			return redirect()->back()->withErrors(trans('shared::message.error.check_already'))->withInput();
		}
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param                          $offlinePaymentId
	 * @param                          $instituteId
	 * @param                          $courseId
	 * @param                          $totalBuyer
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function generateCoupon( Request $request, $offlinePaymentId, $instituteId, $courseId, $totalBuyer ) {
		$tranStatus = false;
		$courseVerifyOfflinePaymentRepo = App::make(CourseVerifyOfflinePaymentRepo::class);

		$couponCodes = event('coupon-generate', [
			$offlinePaymentId,
			$instituteId,
			$courseId,
			$totalBuyer
		]);

		if ( ! empty($couponCodes) ) {
			$arrayFieldsValue = array();
			$arrayFieldsValue['is_coupon_generated'] = 1;

			$updateCouponDetails = $courseVerifyOfflinePaymentRepo->updateCouponCode($arrayFieldsValue, $offlinePaymentId);

			if ( $updateCouponDetails ) {
				$tranStatus = true;
				DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $offlinePaymentId, 'generateCoupon', $request->getRequestUri(), Session::get('user_id'), $arrayFieldsValue);

				return redirect()->back()->with('message', trans('shared::message.success.process'))->withInput();
			} else {
				$tranStatus = false;
				DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $offlinePaymentId, 'generateCouponFail', $request->getRequestUri(), Session::get('user_id'), $arrayFieldsValue);

				return redirect()->back()->withErrors(trans('shared::message.error.check_already'))->withInput();
			}
		}
	}
}
