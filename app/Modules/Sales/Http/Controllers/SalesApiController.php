<?php

namespace App\Modules\Sales\Http\Controllers;

use App;
use App\Common\ErrorCodes;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Sales\Repositories\Contracts\AfterSalesVisitRepo;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class SalesApiController
 * @package App\Modules\Sales\Http\Controllers
 */
class SalesApiController extends Controller {

	/**
	 * @var InstInquiryRepo
	 */
	protected $instInquiryRepo;
	/**
	 * @var SalesVisitRepo
	 */
	protected $salesVisitRepo;

	/**
	 * SalesApiController constructor.
	 *
	 * @param InstInquiryRepo $instInquiryRepo
	 * @param SalesVisitRepo  $salesVisitRepo
	 */
	public function __construct( InstInquiryRepo $instInquiryRepo, SalesVisitRepo $salesVisitRepo ) {
		$this->instInquiryRepo = $instInquiryRepo;
		$this->salesVisitRepo = $salesVisitRepo;
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @param  int                     $instituteUserId
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getLastAfterSaleVisitOfInstitute( Request $request, $instituteUserId ) {
		$result = ['status' => 0, 'data' => ''];
		$request->merge(['id' => GeneralHelpers::decode($instituteUserId)]);

		try {
			$this->validate($request, [
				'id' => 'required|numeric|institute'
			]);
		} catch ( ValidationException $exception ) {

			return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
		}
		$contactDetailColumns = [
			'contact_person',
			'contact_person_desig',
			'contact_person_phone',
			'contact_person_email_id'
		];
		$afterSalesVisitDetail = App::make(AfterSalesVisitRepo::class)
		                            ->getAfterSalesVisitDetailByInstituteId($request->input('id'), $contactDetailColumns);
		$result['status'] = 1;

		if ( ! empty($afterSalesVisitDetail) ) {
			$result['data']['after_sales_visit'] = array_only($afterSalesVisitDetail, $contactDetailColumns);
		} else {
			/* If not found contact details in after sales, then fetch from pre sales */
			$afterSalesVisitDetail = $this->instInquiryRepo->getDetailWithLatestVisitDetails(null, [TABLE_BACKOFFICE_INST_INQUIRY . '.converted_inst_id' => $request->input('id')], $contactDetailColumns);

			if ( ! $afterSalesVisitDetail->isEmpty() ) {
				/* With custom where we get collection instance, so fetch first record's contact detail */
				$result['data']['after_sales_visit'] = array_only($afterSalesVisitDetail[0], $contactDetailColumns);
			}
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * Get Available Cities
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAvailableCities( Request $request ) {
		$result = ['status' => 0, 'items' => ''];
		try {
			$this->validate($request, [
				'term' => 'required|alpha'
			]);
		} catch ( ValidationException $exception ) {

			return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
		}

		$cities = $this->instInquiryRepo->getAvailableCities(GeneralHelpers::clearParam($request->input('term'), PARAM_RAW_TRIMMED));

		if ( ! $cities->isEmpty() ) {
			$result['status'] = 1;
			$result['items'] = $cities;
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * Get Available Designations for inst call visit
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAvailableDesignations( Request $request ) {
		$result = ['status' => 0, 'items' => ''];
		try {
			$this->validate($request, [
				'term' => 'required|alpha_space'
			]);
		} catch ( ValidationException $exception ) {
			return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
		}
		$designations = $this->salesVisitRepo->getAvailableDesignations(GeneralHelpers::clearParam($request->input('term'), PARAM_RAW_TRIMMED));

		if ( ! $designations->isEmpty() ) {
			$result['status'] = 1;
			$result['items'] = $designations;
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * Get Available Designations for after sales visit
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAvailableDesignationsForAfterSalesVisit( Request $request ) {
		$result = ['status' => 0, 'items' => ''];
		try {
			$this->validate($request, [
				'term' => 'required|alpha'
			]);
		} catch ( ValidationException $exception ) {
			return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
		}
		$designations = App::make(AfterSalesVisitRepo::class)
		                   ->getAvailableDesignations(GeneralHelpers::clearParam($request->input('term'), PARAM_RAW_TRIMMED));

		if ( ! $designations->isEmpty() ) {
			$result['status'] = 1;
			$result['items'] = $designations;
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * Get Not Acquired List of Institute
	 *
	 * @param int $instituteInquiryId inquiry id of the institute
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getNotAcquiredInstitute( $instituteInquiryId ) {
		$result = ['status' => 0, 'items' => ''];
		$institute = $this->instInquiryRepo->getDetailWithLatestVisitDetails($instituteInquiryId);
		$statusCode = ErrorCodes::HTTP_OK;

		if ( ! empty($institute) ) {
			$result['status'] = 1;
			$result['items'] = $institute;
		} else {
			$statusCode = ErrorCodes::HTTP_BAD_REQUEST;
		}

		return $this->sendResponse($result, $statusCode);
	}
}
