<?php

namespace App\Modules\Report\Http\Controllers\API;

use App;
use App\Common\ErrorCodes;
use App\Common\GeneralHelpers;
use App\Modules\Report\Http\Requests\InstituteList\API\EditInstituteInquiryRequest;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DB;
use DBLog;
use Exception;
use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class InstituteListAPIController
 * @package App\Modules\Report\Http\Controllers\API
 */
class InstituteListAPIController extends Controller {

	/**
	 * Get the inquiry details of institute
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getInstituteInquiryDetails( Request $request ) {
		/** @var InstInquiryRepo $instituteInquiryRepo */
		$instituteInquiryRepo = App::make(InstInquiryRepo::class);

		$inquiryDetails = $instituteInquiryRepo->getDetail(null, ['converted_inst_id' => $request->get('decryptedId')]);
		$inquiryDetails = GeneralHelpers::encryptColumns($inquiryDetails, ['inst_category_id', 'acq_member_id']);
		$results = ['status' => 1, 'data' => $inquiryDetails->first()];

		return $this->sendResponse($results);
	}

	/**
	 * Set the inquiry detail of institute
	 *
	 * @param EditInstituteInquiryRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function editInstituteInquiryDetails( EditInstituteInquiryRequest $request ) {
		$result = ['status' => 0];
		$categoryId = GeneralHelpers::decode($request->get('category_id'));
		$memberId = GeneralHelpers::decode($request->get('member_id'));
		$city = GeneralHelpers::clearParam($request->get('city'), PARAM_RAW_TRIMMED);
		$userId = $request->get('decryptedId');

		/** @var UserMasterRepo $userMasterRepo */
		$userMasterRepo = App::make(UserMasterRepo::class);
		$institute = $userMasterRepo->getInstituteByOwnerId($userId, [], false);

		if ( ! isset($institute['user_school_name']) && empty($institute['user_school_name']) ) {
			$result['message'] = trans('exception.invalid_request.message');
			$result['code'] = trans('exception.invalid_request.code');
			$result['errors']['message'] = trans('exception.resource_not_found.message',
				['resource' => 'user_school_name']);

			return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
		}

		/** @var InstInquiryRepo $instituteInquiryRepo */
		$instituteInquiryRepo = App::make(InstInquiryRepo::class);

		$inquiryDetails = $instituteInquiryRepo->getDetail(null, ['converted_inst_id' => $userId],
			['inst_list_acq', 'acq_status'], 'first');

		if ( ! empty($inquiryDetails['inst_inquiry_id']) && $inquiryDetails['inst_list_acq'] == 0 && $inquiryDetails['acq_status'] == 1 ) {

			/** @var SalesTeamRepo $salesTeamRepo */
			$salesTeamRepo = App::make(SalesTeamRepo::class);
			$member = $salesTeamRepo->getMemberDetail($inquiryDetails['acq_member_id']);
			$result['message'] = trans('report::institute-list.error.acq_done', ['member' => $member['first_name']]);

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		} else {
			$time = Helper::datetimeToTimestamp();
			$ips = Helper::getIPAddress();
			$salesVisitRepo = App::make(SalesVisitRepo::class);
			$acquisitionResult = null;
			try {
				DB::beginTransaction();
				if ( ! empty($inquiryDetails['inst_inquiry_id']) ) {
					// Institute inquiry id is not empty, so edit the inquiry entry and sales visit entry

					$inquiryData = [];
					$inquiryData['city'] = $city;
					$inquiryData['acq_status'] = 1;
					$inquiryData['inst_category_id'] = $categoryId;
					$inquiryData['acq_date'] = $time;
					$inquiryData['acq_member_id'] = $memberId;
					$inquiryData['inst_list_acq'] = 1;
					$inquiryData['inst_list_acq_dt'] = $time;
					$inquiryData['user_ip'] = $ips;
					$inquiryData['updated'] = $time;
					$inquiryData['updated_user'] = \Request::get('auth_user')['user_id'];

					$inquiryUpdated = $instituteInquiryRepo->updateInquiry($inquiryData,
						$inquiryDetails['inst_inquiry_id']);

					if ( isset($inquiryUpdated['inst_inquiry_id']) && $inquiryDetails['inst_inquiry_id'] == $inquiryUpdated['inst_inquiry_id'] ) {

						DBLog::save(LOG_MODULE_ACQUISITION, $inquiryUpdated['inst_inquiry_id'], "update",
							$request->getRequestUri(), \Request::get('auth_user')['user_id'], $inquiryData);

						$salesVisitData = [];
						$salesVisitData['visit_date'] = $time;
						$salesVisitData['contact_person'] = "";
						$salesVisitData['contact_person_desig'] = "";
						$salesVisitData['contact_person_phone'] = "";
						$salesVisitData['inst_inquiry_id'] = $inquiryUpdated['inst_inquiry_id']; // auto increment id from backoffice_inst_inquiry table
						$salesVisitData['remarks'] = "";
						$salesVisitData['acq_status'] = 1;
						$salesVisitData['inst_list_acq'] = 1;
						$salesVisitData['inst_list_acq_dt'] = $time;
						$salesVisitData['user_ip'] = $ips;
						$salesVisitData['member_id'] = $memberId;
						$salesVisitData['updated'] = $time;
						$salesVisitData['updated_user'] = \Request::get('auth_user')['user_id'];
						$salesVisitData['device_type'] = 'BACKOFFICE';

						$where = [
							'inst_inquiry_id' => $inquiryUpdated['inst_inquiry_id'],
							'inst_list_acq'   => 1,
							'acq_status'      => 1
						];

						$salesVisitRepo->updateSalesVisit($salesVisitData, null, $where);

						DBLog::save(LOG_MODULE_SALES_VISIT, $inquiryUpdated['inst_inquiry_id'], "update",
							$request->getRequestUri(), \Request::get('auth_user')['user_id'], $salesVisitData);
					}
				} else {
					// There is no entry of institute inquiry, So add the entry of institute inquiry and
					// update the data to sales visit
					$inquiryData = [];
					$inquiryData['institute_name'] = $institute['user_school_name'];
					$inquiryData['address'] = "";
					$inquiryData['city'] = $city;
					$inquiryData['state_id'] = 0;
					$inquiryData['student_strength'] = 0;
					$inquiryData['acq_status'] = 1;
					$inquiryData['converted_inst_id'] = $userId;
					$inquiryData['inst_category_id'] = $categoryId;
					$inquiryData['acq_date'] = $time;
					$inquiryData['acq_member_id'] = $memberId;
					$inquiryData['inst_list_acq'] = 1;
					$inquiryData['inst_list_acq_dt'] = $time;
					$inquiryData['user_ip'] = $ips;
					$inquiryData['inserted'] = $time;
					$inquiryData['inserted_user'] = \Request::get('auth_user')['user_id'];

					$inquiry = $instituteInquiryRepo->createInquiry($inquiryData);

					if ( ! empty($inquiry) ) {

						DBLog::save(LOG_MODULE_ACQUISITION, $inquiry['inst_inquiry_id'], "insert",
							$request->getRequestUri(), \Request::get('auth_user')['user_id'], $inquiryData);

						$salesVisitData = [];
						$salesVisitData['visit_date'] = $time;
						$salesVisitData['contact_person'] = "";
						$salesVisitData['contact_person_desig'] = "";
						$salesVisitData['contact_person_phone'] = "";
						$salesVisitData['inst_inquiry_id'] = $inquiry['inst_inquiry_id']; // auto increment id from backoffice_inst_inquiry table
						$salesVisitData['remarks'] = "";
						$salesVisitData['acq_status'] = 1;
						$salesVisitData['inst_list_acq'] = 1;
						$salesVisitData['inst_list_acq_dt'] = $time;
						$salesVisitData['user_ip'] = $ips;
						$salesVisitData['member_id'] = $memberId;
						$salesVisitData['inserted'] = $time;
						$salesVisitData['inserted_user'] = \Request::get('auth_user')['user_id'];
						$salesVisitData['device_type'] = 'BACKOFFICE';

						$salesVisitRepo->createSalesVisit($salesVisitData);

						DBLog::save(LOG_MODULE_SALES_VISIT, $inquiry['inst_inquiry_id'], "insert",
							$request->getRequestUri(), \Request::get('auth_user')['user_id'], $salesVisitData);
					}
				}
				DB::commit();
				$result['status'] = 1;
				$result['message'] = trans('shared::message.success.process');

				return $this->sendResponse($result, ErrorCodes::HTTP_OK);
			} catch ( Exception $exception ) {
				DB::rollBack();

				GeneralHelpers::logException($exception);
				$result['message'] = trans('shared::message.error.something_wrong');

				return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
			}
		}
	}

	/**
	 * Activate plan ajax
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function activatePlan( Request $request ) {
		$userId = $request->get('decryptedId');

		DBLog::save(LOG_MODULE_PLAN, $userId, "activate", $request->getRequestUri(),
			\Request::get('auth_user')['user_id']);

		if ( \Request::get('auth_user')['user_id'] != BACKOFFICE_ADMIN_ID ) {
			$result['message'] = trans('exception.something_wrong.message');

			return $this->sendResponse($result, ErrorCodes::HTTP_UNAUTHORIZED);
		}

		$result = event('activate-plan', [$userId, \Request::get('auth_user')['user_id']])[0];

		if ( isset($result['status']) && $result['status'] == 1 ) {
			$result['message'] = trans('shared::message.success.process');

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

	/**
	 * Deactivate plan ajax
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deactivatePlan( Request $request ) {
		$userId = $request->get('decryptedId');
		$result['status'] = 0;

		DBLog::save(LOG_MODULE_PLAN, $userId, "deactivate", $request->getRequestUri(),
			\Request::get('auth_user')['user_id']);

		if ( \Request::get('auth_user')['user_id'] != BACKOFFICE_ADMIN_ID ) {
			$result['message'] = trans('exception.something_wrong.message');

			return $this->sendResponse($result, ErrorCodes::HTTP_UNAUTHORIZED);
		}

		$result = event('deactivate-plan', [$userId, \Request::get('auth_user')['user_id']])[0];

		if ( isset($result['status']) && $result['status'] == 1 ) {
			$result['message'] = trans('shared::message.success.process');

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

	/**
	 * Verify plan ajax
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function verifyPlan( Request $request ) {
		$userId = $request->get('decryptedId');
		$remarks = null;

		DBLog::save(LOG_MODULE_PLAN, $userId, "verify", $request->getRequestUri(),
			\Request::get('auth_user')['user_id']);

		if ( $request->has('remarks') ) {
			$remarks = GeneralHelpers::clearParam($request->get('remarks'), PARAM_RAW_TRIMMED);
		}

		$result = event('verify-plan', [$userId, $remarks, \Request::get('auth_user')['user_id']])[0];

		if ( isset($result['status']) && $result['status'] == 1 ) {
			$result['message'] = trans('shared::message.success.process');

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

	/**
	 * Cancel plan ajax
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function cancelPlan( Request $request ) {
		$userId = $request->get('decryptedId');
		$remarks = null;

		DBLog::save(LOG_MODULE_PLAN, $userId, "cancel", $request->getRequestUri(),
			\Request::get('auth_user')['user_id']);

		if ( $request->has('remarks') ) {
			$remarks = GeneralHelpers::clearParam($request->get('remarks'), PARAM_RAW_TRIMMED);
		}

		$result = event('cancel-plan', [$userId, \Request::get('auth_user')['user_id'], 0, $remarks])[0];

		if ( isset($result['status']) && $result['status'] == 1 ) {
			$result['message'] = trans('shared::message.success.process');

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}
}

