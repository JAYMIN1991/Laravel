<?php

namespace App\Modules\Account\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\URLHelpers;
use App\Modules\Account\Repositories\Contracts\CourseOrdersSummaryRepo;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use App\Modules\Shared\Misc\CourseOrderPaidSummary;
use App\Modules\Shared\Misc\CourseOrderSummary;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;

/**
 * Class CourseOrdersController
 * @package App\Modules\Account\Http\Controllers
 */
class CourseOrdersController extends Controller {

	/**
	 * @param Request $request
	 */
	public function index( Request $request ) {

		// Generate invoice common URL
		$generateInvoiceURL = HTTP_SERVER_CATALOG . 'app/download/';

		$statusComplete = CourseOrderSummary::SELECT_ORDER_STATUS_COMPLETED;

		// Get institute name when institute id is got
		if ( $request->has('institute_id') ) {
			$instituteName = App::make(UserMasterRepo::class)
			                    ->getInstituteByOwnerId(GeneralHelpers::decode($request->get('institute_id')))['user_school_name'];
		}

		// Get default date format
		$todayDateDefault = (string) Helper::getDate(trans('shared::config.output_date_format'));

		// Get order status options list
		$orderStatusOptions = array(
			CourseOrderSummary::SELECT_ORDER_STATUS_INITIALIZED => trans('account::course-order.common.initialized'),
			CourseOrderSummary::SELECT_ORDER_STATUS_IN_SESSION  => trans('account::course-order.common.in_session'),
			CourseOrderSummary::SELECT_ORDER_STATUS_PROCESSING  => trans('account::course-order.common.processing'),
			CourseOrderSummary::SELECT_ORDER_STATUS_CANCELLED   => trans('account::course-order.common.cancelled'),
			CourseOrderSummary::SELECT_ORDER_STATUS_FAILED      => trans('account::course-order.common.failed'),
			CourseOrderSummary::SELECT_ORDER_STATUS_COMPLETED   => trans('account::course-order.common.completed')
		);
		// Get paid status options
		$paidStatusOptions = array(
			ViewHelper::SELECT_OPTION_VALUE_ANY            => '-- ' . trans('account::user-commission.common.any') . ' --',
			CourseOrderPaidSummary::SELECT_PAID_STATUS_YES => trans('account::course-order.common.yes'),
			CourseOrderPaidSummary::SELECT_PAID_STATUS_NO  => trans('account::course-order.common.no')
		);

		if ( $request->has('btnsearch') ) {

			// Get Course Order summary list search by criteria
			$CourseOrderSummary = App::make(CourseOrdersSummaryRepo::class)
			                         ->getCourseOrderSummaryResult(true)
			                         ->appends($request->except('page'));

			// Check login user is admin
			$isAdmin = App::make(AdminUsersRepo::class)->isSiteAdmin(Session::get('user_id'));
			// Create buyer and seller invoice download url
			if ( isset($isAdmin) ) {

				foreach ( $CourseOrderSummary as $key => $value ) {

					// Buyer invoice url
					if ( ! empty($value['buyer_invoice_filename']) ) {
						$value['download_buyer_invoice_url'] = $generateInvoiceURL . '?file=' . URLHelpers::encodeGetParam(DIR_WS_RESOURCES_BUYER_INVOICES . $value['buyer_invoice_filename']) . '&dsrc=' . URLHelpers::encodeGetParam('backoffice') . '&tgt=' . URLHelpers::encodeGetParam('s3') . '&m=' . URLHelpers::encodeGetParam('buyer_invoice') . '&u=' . URLHelpers::encodeGetParam(Session::get('user_id'));
						$CourseOrderSummary[$key] = $value;
					} else {
						$value['generate_buyer_invoice_url'] = route('api.account.generate-invoice', [
							'do_action' => URLHelpers::encodeGetParam('generate_buyer_invoice'),
							'trans_id'  => URLHelpers::encodeGetParam($value['trans_id']),
							'user_id'   => URLHelpers::encodeGetParam($value['course_owner']),
							'is_send'   => URLHelpers::encodeGetParam('1')
						]);
						$CourseOrderSummary[$key] = $value;
					}

					// Seller invoice url
					if ( ! empty($value['seller_invoice_filename']) ) {
						$value['download_seller_invoice_url'] = $generateInvoiceURL . '?file=' . URLHelpers::encodeGetParam(DIR_WS_RESOURCES_SELLER_INVOICES . $value['seller_invoice_filename']) . '&dsrc=' . URLHelpers::encodeGetParam('backoffice') . '&tgt=' . URLHelpers::encodeGetParam('s3') . '&m=' . URLHelpers::encodeGetParam('seller_invoice') . '&u=' . URLHelpers::encodeGetParam(Session::get('user_id'));
						$CourseOrderSummary[$key] = $value;
					} else {
						$value['generate_seller_invoice_url'] = route('api.account.generate-invoice', [
							'do_action' => URLHelpers::encodeGetParam('generate_seller_invoice'),
							'trans_id'  => URLHelpers::encodeGetParam($value['trans_id']),
							'user_id'   => URLHelpers::encodeGetParam($value['course_owner']),
							'is_send'   => URLHelpers::encodeGetParam('1')
						]);
						$CourseOrderSummary[$key] = $value;
					}

				}
			}
			// Save search log to DB
			DBLog::save(LOG_MODULE_COURSE_ORDER_SUMMARY, GeneralHelpers::decode($request->get('institute_id')), 'search', $request->getRequestUri(), Session::get('user_id'), $CourseOrderSummary->all());
		}

		// Export course order summary report
		if ( $request->has('btnexport') && $request->input('btnexport') == 'export' ) {

			$courseOrderReports = $CourseOrderSummary = App::make(CourseOrdersSummaryRepo::class)
			                                               ->getCourseOrderSummaryResult(false);
			$exportColumnNames = [
				'trans_id'             => 'Order ID',
				'trans_dt'             => 'Order Date',
				'course_name'          => 'Course Name',
				'total_invoice'        => 'Course Price',
				'total_charges'        => 'Gateway Charges',
				'total_commission'     => 'Flinnt Charges',
				'total_seller_invoice' => 'Payable Amount',
				'payment_mode'         => 'Payment Mode',
				'billing_name'         => 'Buyer Name',
				'billing_phone'        => 'Buyer Phone',
				'billing_email'        => 'Buyer email',
				'trans_status_label'   => 'Order Status',
				'is_paid_seller_label' => 'Is Paid'
			];
			// DB log for export event
			DBLog::save(LOG_MODULE_COURSE_ORDER_SUMMARY, GeneralHelpers::decode($request->get('institute_id')), 'export', $request->getRequestUri(), Session::get('user_id'), $courseOrderReports->all());
			// Export to excel 
			GeneralHelpers::exportToExcel($exportColumnNames, $courseOrderReports->all(), FILENAME_CONTENT_USER_REPORT);
		}

		$courseOrderData = compact('instituteName', 'orderStatusOptions', 'paidStatusOptions', 'todayDateDefault', 'CourseOrderSummary', 'markAsPaidUrl', 'statusComplete');

		return View::make('account::course-orders', $courseOrderData);
	}
}
