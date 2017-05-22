<?php

namespace App\Modules\Course\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Course\Http\Requests\OfflinePayment\StoreRequest;
use App\Modules\Course\Http\Requests\OfflinePayment\IndexRequest;
use App\Modules\Course\Http\Requests\OfflinePayment\UpdateRequest;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use App\Modules\Course\Repositories\Criteria\SearchCommonOfflinePaymentCrit;
use App\Modules\Course\Repositories\Criteria\SearchOfflinePaymentCrit;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use View;

class OfflinePaymentController extends Controller {

	/**
	 * @param \App\Modules\Course\Http\Requests\OfflinePayment\IndexRequest $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index( IndexRequest $request ) {
		if ( $request->has('institute_id') ) {
			$institute = $this->getSelectedInstituteDetails($request->get('decoded_institute_id'));

			if ( ! empty($institute) ) {
				$selectedCourseId = $request->input('course_id');

				/**@var Collection $courses */
				$courses = $this->getInstituteCourses($request->input('decoded_institute_id'));
			}
		}
		// Repo for course Offline payment
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

		// If submit form for searching
		if ( $request->exists('button_search') ) {
			//Apply search criteria for offline payment
			$courseOfflinePaymentRepo->pushCriteria(app(SearchOfflinePaymentCrit::class));
			$courseOfflinePaymentRepo->pushCriteria(app(SearchCommonOfflinePaymentCrit::class));
			// Get offline payment course and institute collection
			$offlinePaymentRecords = $courseOfflinePaymentRepo->getOfflinePaymentRecords(true)
			                                                  ->appends($request->except('page', 'decode_institute_id', 'decode_course_id'));

			// Check is Offline Payment is not empty
			if ( ! $offlinePaymentRecords->isEmpty() ) {
				foreach ( $offlinePaymentRecords as $key => $value ) {
					if ( $value['coupon_codes'] ) {
						// Add new line after each coupon code
						$value['coupon_codes'] = nl2br($value['coupon_codes']);
						$offlinePaymentRecords[$key] = $value;
					}
					if ( $value['offline_payment_id'] ) {
						// Encrypt offline_payment_id
						$value['offline_payment_id'] = GeneralHelpers::encode($value['offline_payment_id']);
						$offlinePaymentRecords[$key] = $value;
					}
				}
			}
		}

		// Export Data to excel sheet
		if ( $request->exists('button_export') ) {
			$offlinePaymentRecords = $courseOfflinePaymentRepo->getOfflinePaymentRecords(false);
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
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, GeneralHelpers::decode($request->get('institute_id')), 'export', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentRecords->all());
			// Export to excel
			GeneralHelpers::exportToExcel($exportColumnNames, $offlinePaymentRecords->all(), EXPORT_OFFLINE_COUPON);
		}
		$offlineData = compact('institute', 'selectedCourseId', 'courses', 'offlinePaymentRecords');

		return View::make('course::offline.index', $offlineData);
	}

	/**
	 * Get selected institute details by institute id
	 *
	 * @param $instituteId
	 *
	 * @return array|mixed
	 */
	protected function getSelectedInstituteDetails( $instituteId ) {
		$institute = App::make(UserMasterRepo::class)
		                ->getActiveInstituteHavingPublicCoursesByOwnerId(GeneralHelpers::clearParam($instituteId, PARAM_RAW_TRIMMED));
		if ( ! empty($institute) ) {
			$institute = GeneralHelpers::encryptColumns($institute, 'user_id');
			$institute = [$institute['user_id'] => $institute['user_school_name']];

			return $institute;
		} else {
			return [];
		}
	}

	/**
	 * Get course details from institute id
	 *
	 * @param $instituteId
	 *
	 * @return array|mixed
	 */
	protected function getInstituteCourses( $instituteId ) {
		$courses = App::make(CourseOfflinePaymentRepo::class)->getOfflinePaymentCoursesByInstituteId( $instituteId );

		if ( ! $courses->isEmpty() ) {
			$courses = GeneralHelpers::encryptColumns($courses->pluck('course_name', 'id'));
		} else {
			$courses = [];
		}

		return $courses;
	}

	/**
	 * Delete Offline payment record by offline payment id
	 * @param \Illuminate\Http\Request $request
	 * @param                          $offlinePaymentId
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function destroy( Request $request, $offlinePaymentId ) {
		// Repo for course Offline payment
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

		$destroyRecord = $courseOfflinePaymentRepo->destroyOfflinePaymentRecord(GeneralHelpers::decode($offlinePaymentId));

		if ( $destroyRecord ) {
			// DB log for delete event
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, GeneralHelpers::decode($offlinePaymentId), 'Delete', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentId);

			return redirect()
				->route('course.offline.index')
				->with('message', trans('shared::message.success.delete_record'));
		} else {
			// DB log for delete event error
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, GeneralHelpers::decode($offlinePaymentId), 'DeleteCorrection', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentId);

			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Edit request method by offline_payment_id
	 * @param $requestId
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit( $requestId ) {
		// Encode request id for security reason
		if ( $requestId ) {
			$requestId = GeneralHelpers::decode($requestId);
		}

		$offlinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);
		$offlinePaymentRecord = $offlinePaymentRepo->getOfflinePaymentDetails($requestId);

		// If offline payment records not empty then fill data to input
		if ( ! empty($offlinePaymentRecord) ) {
			$courseRepo = App::make(CourseRepo::class);
			$courseData = $courseRepo->getCourseAndCourseTypeByCourseId($offlinePaymentRecord['course_id'], [
				'course_max_subscription',
				'course_public_type_id',
				'course_paid_promotion',
				'course_paid_promo_max_subscription',
				'amount'
			]);

			// Get total subscribed user count
			if ( $offlinePaymentRecord['course_id'] ) {
				$offlinePaymentRecord['subscribed_users'] = $offlinePaymentRepo->getCourseCanUserSubscribe($offlinePaymentRecord['course_id'], USER_COURSE_ROLE_LEARNER);
			}

			// Get Maximum subscription for perticular course
			if ( $courseData['course_paid_promotion'] ) {
				$offlinePaymentRecord['max_subscription_limit'] = $courseData['course_paid_promo_max_subscription'];
			} else {
				$offlinePaymentRecord['max_subscription_limit'] = $courseData['course_max_subscription'];
			}

			// Get course Price
			$offlinePaymentRecord['course_price'] = number_format($courseData['amount'], 2);

			if ( $courseData['course_public_type_id'] == COURSE_TYPE_PRIVATE ) {
				$courseType = 'Private';
			}

			// Define course type by course_public_type_id
			if ( $courseData['course_public_type_id'] == COURSE_TYPE_TIMEBOUND ) {
				$courseType = 'Time-Bound';
			}

			if ( $courseData['course_public_type_id'] == COURSE_TYPE_SELFPACED ) {
				$courseType = 'Self-Paced';
			}

			$offlinePaymentRecord['course_type'] = $courseType;
		}

		$institutes = App::make(UserMasterRepo::class)->getInstituteByOwnerId($offlinePaymentRecord['institute_id']);

		$courses = App::make(CourseRepo::class)
		              ->getCourseAndCourseTypeByCourseId($offlinePaymentRecord['course_id'], ['course_name']);

		// Keep this code
		//$memberDetails = App::make(SalesTeamRepo::class)->getMemberDetail($offlinePaymentRecord['member_id']);

		if ( $institutes ) {
			$institute = [GeneralHelpers::encode($institutes['user_id']) => $institutes['user_school_name']];
		}

		if ( $courses ) {
			$courses = [GeneralHelpers::encode($courses['course_id']) => $courses['course_name']];
		}
		// Get Sales member list
		$members = $this->getMemberList();

		$data = compact(['offlinePaymentRecord', 'institute', 'courses', 'members']);

		return View::make('course::offline.edit', $data);
	}

	/**
	 * Get sales team member list
	 * @return mixed
	 */
	protected function getMemberList() {
		// Create object for sales team
		$salesTeamRepo = App::make(SalesTeamRepo::class);
		// Get member List
		$memberList = $salesTeamRepo->getListForReportedTo();

		return $memberList;
	}

	/**
	 * Get export coupon code for perticular course
	 * @param \Illuminate\Http\Request $request
	 * @param                          $offlinePaymentId
	 */
	public function export( Request $request, $offlinePaymentId ) {
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

		if ( $offlinePaymentId ) {
			$offlinePaymentId = GeneralHelpers::decode($offlinePaymentId);
			$offlinePaymentRecords = $courseOfflinePaymentRepo->getOfflinePaymentRecords(false, $offlinePaymentId);

			// Define db column name and field name
			$exportColumnNames = [
				'coupon_codes' => 'Coupon Codes'
			];

			// DB log for export event
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, GeneralHelpers::decode($request->get('institute_id')), 'export', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentRecords->all());
			// Export to excel
			GeneralHelpers::exportToExcel($exportColumnNames, $offlinePaymentRecords->all(), EXPORT_OFFLINE_COUPON);
		}
	}

	/**
	 * Update offline payment information
	 * @param \App\Modules\Course\Http\Requests\OfflinePayment\UpdateRequest $request
	 * @param                                                                $offlinePaymentId
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateRequest $request, $offlinePaymentId ) {

		if ( $offlinePaymentId ) {
			$instrumentDateArray = explode("/", $request->get('cheque_date'));

			$offlinePaymentData['institute_id'] = GeneralHelpers::decode($request->input('institute_id'));
			$offlinePaymentData['course_id'] = GeneralHelpers::decode($request->input('course_id'));
			$offlinePaymentData['member_id'] = GeneralHelpers::clearParam($request->input('member_list'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['total_buyer'] = GeneralHelpers::clearParam($request->input('total_quantity'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['amount_paid'] = GeneralHelpers::clearParam($request->input('cheque_amount'), PARAM_RAW_TRIMMED);

			$offlinePaymentData['instrument_type'] = GeneralHelpers::clearParam(INSTRUMENT_TYPE_CHEQUE, PARAM_RAW_TRIMMED);
			$offlinePaymentData['instrument_no'] = GeneralHelpers::clearParam($request->input('cheque_no'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['instrument_date'] = mktime(0, 0, 0, $instrumentDateArray[1], $instrumentDateArray[0], $instrumentDateArray[2]);
			$offlinePaymentData['instrument_issuer_name'] = GeneralHelpers::clearParam($request->input('bank_name'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['instrument_issuer_sub'] = GeneralHelpers::clearParam($request->input('branch_name'), PARAM_RAW_TRIMMED);

			$offlinePaymentData['billing_name'] = GeneralHelpers::clearParam($request->input('billing_name'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_address'] = GeneralHelpers::clearParam($request->input('billing_address'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_city'] = GeneralHelpers::clearParam($request->input('billing_city'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_state'] = GeneralHelpers::clearParam($request->input('billing_state'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_pincode'] = GeneralHelpers::clearParam($request->input('billing_pincode'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_phone'] = GeneralHelpers::clearParam($request->input('billing_phone'), PARAM_RAW_TRIMMED);
			$offlinePaymentData['billing_email'] = GeneralHelpers::clearParam($request->input('billing_email'), PARAM_RAW_TRIMMED);

			$offlinePaymentData['remarks'] = GeneralHelpers::clearParam($request->input('remark'), PARAM_RAW_TRIMMED);

			$offlinePaymentData['user_ip'] = \Helper::getIPAddress();
			$offlinePaymentData['device_type'] = 'BACKOFFICE';
			// Course Repository
			$courseRepo = App::make(CourseRepo::class);
			// Course Offline payment repository
			$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

			$coursePrice = $courseRepo->getCoursePrice($offlinePaymentData['course_id']);
			$offlinePaymentData['actual_course_price'] = $coursePrice['amount'];

			/* calculate course transaction price ---- (check amount / total buyers), round at 4 places */
			$offlinePaymentData['course_tran_price'] = round(($offlinePaymentData['amount_paid'] / $offlinePaymentData['total_buyer']), 4);

			/******************************** calculate commission percent ******************************/
			$courseTypeDetails = $courseRepo->getCourseAndCourseTypeByCourseId($offlinePaymentData['course_id'], ['course_public_type_id']);

			$offlinePaymentData['commission_percent'] = $courseOfflinePaymentRepo->getCourseCommission($courseTypeDetails['course_public_type_id'], $offlinePaymentData['institute_id']);

			$offlinePaymentData['payment_status'] = OFFLINE_PAY_STATUS_UNCONFIRMED;

			// Update data in pay offline table
			$offlinePaymentData['updated'] = time();
			$offlinePaymentData['updated_user'] = Session::get('user_id');

			$updateOfflineRecord = App::make(CourseOfflinePaymentRepo::class)
			                          ->updateOfflinePaymentRecord($offlinePaymentData, $offlinePaymentId);
		}
		if ( $updateOfflineRecord ) {
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, $offlinePaymentId, 'update', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentData);

			return redirect()->route('course.offline.index')->with('message', trans('shared::message.success.process'));
		} else {
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, $offlinePaymentId, 'updateFailure', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentData);

			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Create new record of offline payment
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function create( Request $request ) {
		// Get members list
		$members = $this->getMemberList();

		$data = compact('institute', 'members');

		return View::make('course::offline.create', $data);
	}

	/**
	 * Insert offline payment data to DB
	 * @param  $request
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function store( StoreRequest $request ) {
		// Course Offline payment repository
		$courseOfflinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);
		// Course Repository
		$courseRepo = App::make(CourseRepo::class);
		// Get check date and explode for create time
		$instrumentDateArray = explode("/", $request->get('cheque_date'));
		// Offline Payment Details array
		$offlinePaymentDetails['institute_id'] = GeneralHelpers::decode(GeneralHelpers::clearParam($request->input('institute_id'), PARAM_RAW_TRIMMED));
		$offlinePaymentDetails['course_id'] = GeneralHelpers::decode(GeneralHelpers::clearParam($request->input('course_id'), PARAM_RAW_TRIMMED));
		$offlinePaymentDetails['member_id'] = GeneralHelpers::clearParam($request->get('member_list'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['total_buyer'] = GeneralHelpers::clearParam($request->get('total_quantity'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['amount_paid'] = GeneralHelpers::clearParam($request->get('cheque_amount'), PARAM_RAW_TRIMMED);
		// Bank Details array
		$offlinePaymentDetails['instrument_type'] = GeneralHelpers::clearParam(INSTRUMENT_TYPE_CHEQUE, PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['instrument_no'] = GeneralHelpers::clearParam($request->get('cheque_no'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['instrument_date'] = mktime(0, 0, 0, $instrumentDateArray[1], $instrumentDateArray[0], $instrumentDateArray[2]);
		$offlinePaymentDetails['instrument_issuer_name'] = GeneralHelpers::clearParam($request->get('bank_name'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['instrument_issuer_sub'] = GeneralHelpers::clearParam($request->get('branch_name'), PARAM_RAW_TRIMMED);
		// Billing Information array
		$offlinePaymentDetails['billing_name'] = GeneralHelpers::clearParam($request->get('billing_name'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_address'] = GeneralHelpers::clearParam($request->get('billing_address'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_city'] = GeneralHelpers::clearParam($request->get('billing_city'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_state'] = GeneralHelpers::clearParam($request->get('billing_state'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_pincode'] = GeneralHelpers::clearParam($request->get('billing_pincode'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_phone'] = GeneralHelpers::clearParam($request->get('billing_phone'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['billing_email'] = GeneralHelpers::clearParam($request->get('billing_email'), PARAM_RAW_TRIMMED);

		$offlinePaymentDetails['remarks'] = GeneralHelpers::clearParam($request->get('remark'), PARAM_RAW_TRIMMED);
		$offlinePaymentDetails['user_ip'] = \Helper::getIPAddress();
		$offlinePaymentDetails['device_type'] = 'BACKOFFICE';
		$coursePrice = $courseRepo->getCoursePrice($offlinePaymentDetails['course_id']);
		$offlinePaymentDetails['actual_course_price'] = $coursePrice['amount'];

		/* calculate course transaction price ---- (check amount / total buyers), round at 4 places */
		$offlinePaymentDetails['course_tran_price'] = round(($offlinePaymentDetails['amount_paid'] / $offlinePaymentDetails['total_buyer']), 4);

		/******************************** calculate commission percent ******************************/
		$courseTypeDetails = $courseRepo->getCourseAndCourseTypeByCourseId($offlinePaymentDetails['course_id'], ['course_public_type_id']);

		$offlinePaymentDetails['commission_percent'] = $courseOfflinePaymentRepo->getCourseCommission($courseTypeDetails['course_public_type_id'], $offlinePaymentDetails['institute_id']);

		$offlinePaymentDetails['payment_status'] = OFFLINE_PAY_STATUS_UNCONFIRMED;

		// inserting data in pay offline table
		$offlinePaymentDetails['inserted'] = time();
		$offlinePaymentDetails['inserted_user'] = Session::get('user_id');

		//$validateSubscribe = $courseOfflinePaymentRepo->getCourseCanUserSubscribe($request->get('course_id'), $offlinePaymentDetails['total_buyer']);
		// insert Offline Payment records
		$insertOfflinePayment = $courseOfflinePaymentRepo->createOfflinePayment($offlinePaymentDetails);
		if ( $insertOfflinePayment ) {
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, $offlinePaymentDetails['course_id'], 'insert', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentDetails);

			return redirect()->route('course.offline.index')->with('message', trans('shared::message.success.process'));
		} else {
			DBLog::save(LOG_MODULE_COURSE_OFFLINE_PAYMENT, $offlinePaymentDetails['course_id'], 'insert_error', $request->getRequestUri(), Session::get('user_id'), $offlinePaymentDetails);

			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}
}
