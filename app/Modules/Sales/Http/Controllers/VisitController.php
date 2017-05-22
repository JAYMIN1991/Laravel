<?php

namespace App\Modules\Sales\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Location\Repositories\Contracts as LocationRepository;
use App\Modules\Sales\Http\Requests\Visit as Visit;
use App\Modules\Sales\Repositories\Contracts as SalesRepository;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Shared\Misc\SalesVisitViewHelper;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use DB;
use DBLog;
use Exception;
use Helper;
use Illuminate\Http\RedirectResponse;
use Log;
use Redirect;
use Response;
use Session;
use View;

/**
 * Class VisitController
 * @package App\Modules\Sales\Http\Controllers
 */
class VisitController extends Controller {

	/**
	 * @var SalesRepository\SalesTeamRepo $salesTeamRepo Repository of SalesTeam
	 */
	protected $salesTeamRepo;

	/**
	 * @var SalesRepository\SalesVisitRepo $salesVisitRepo Repository of SalesVisit
	 */
	protected $salesVisitRepo;

	/**
	 * @var SalesRepository\InstInquiryRepo $instInquiryRepo Repository of InstInquiry
	 */
	protected $instInquiryRepo;

	/**
	 * @var SalesRepository\InstCategoryRepo $instCategoryRepo Repository of InstCategory
	 */
	protected $instCategoryRepo;

	/**
	 * @var LocationRepository\StatesRepo $statesRepo Repository of State
	 */
	protected $statesRepo;

	/**
	 * InstCallVisitController constructor
	 *
	 * @param SalesRepository\SalesTeamRepo    $salesTeamRepo    Repository of SalesTeam
	 * @param SalesRepository\InstInquiryRepo  $instInquiryRepo  Repository of InstInquiry
	 * @param SalesRepository\InstCategoryRepo $instCategoryRepo Repository of InstCategory
	 * @param LocationRepository\StatesRepo    $statesRepo       Repository of States
	 * @param SalesRepository\SalesVisitRepo   $salesVisitRepo   Repository of SalesVisit
	 */
	public function __construct( SalesRepository\SalesTeamRepo $salesTeamRepo,
	                             SalesRepository\InstInquiryRepo $instInquiryRepo,
	                             SalesRepository\InstCategoryRepo $instCategoryRepo,
	                             LocationRepository\StatesRepo $statesRepo,
	                             SalesRepository\SalesVisitRepo $salesVisitRepo ) {
		$this->salesTeamRepo = $salesTeamRepo;
		$this->instInquiryRepo = $instInquiryRepo;
		$this->instCategoryRepo = $instCategoryRepo;
		$this->statesRepo = $statesRepo;
		$this->salesVisitRepo = $salesVisitRepo;
	}

	/**
	 * Display a listing of the Visit
	 *
	 * @param  Visit\IndexRequest $request
	 *
	 * @return View|RedirectResponse
	 */
	public function index( Visit\IndexRequest $request ) {

		if ( $request->exists('button_export') ) {

			$salesVisits = $this->salesVisitRepo->search(false);

			if ( $salesVisits->isEmpty() ) {
				return Redirect::back()->withErrors(trans('shared::message.error.nothing_to_export'));
			}

			$exportColumnNames = [
				'ref_by'                  => trans('sales::visit.common.visit_by'),
				'visit_date'              => trans('sales::visit.common.visit_date'),
				'institute_name'          => trans('sales::visit.common.institute_name'),
				'category_name'           => trans('sales::visit.common.category'),
				'address'                 => trans('sales::visit.common.address'),
				'city'                    => trans('sales::visit.common.city'),
				'state'                   => trans('sales::visit.common.state'),
				'contact_person'          => trans('sales::visit.common.contact_person'),
				'contact_person_desig'    => trans('sales::visit.common.designation'),
				'contact_person_phone'    => trans('sales::visit.common.phone'),
				'contact_person_email_id' => trans('sales::visit.common.email'),
				'remarks'                 => trans('sales::visit.common.remarks'),
				'acq_status_label'        => trans('sales::visit.index.acq_status')
			];
			$fileName = 'inst_call_visit_list';
			GeneralHelpers::exportToExcel($exportColumnNames, $salesVisits->all(), $fileName);
		}

		$visitDateDefault = (string) Helper::getDate(trans('shared::config.output_date_format'));
		$visitBy = $this->salesTeamRepo->getListByUserId(Session::get('user_id'));
		$inquiryConverted = [
			ViewHelper::SELECT_OPTION_VALUE_ANY => '-- ' . trans('shared::common.dropdown.any') . ' --',
			ViewHelper::SELECT_OPTION_VALUE_YES => trans('shared::common.dropdown.yes'),
			ViewHelper::SELECT_OPTION_VALUE_NO  => trans('shared::common.dropdown.no')
		];
		$categories = $this->instCategoryRepo->getList();
		$institutes = $this->instInquiryRepo->getListOfInstituteNotAcquiredFromInstituteList();
		$salesVisits = $this->salesVisitRepo->search(true)->appends($request->except('page'));

		return View::make('sales::visit.index', compact('visitBy', 'inquiryConverted', 'categories', 'institutes', 'salesVisits', 'visitDateDefault'));
	}

	/**
	 * Show the form for creating a new sales visit
	 *
	 * @return View
	 */
	public function create() {

		$instituteTypes = [
			SalesVisitViewHelper::SELECT_OPTION_NEW_INSTITUTE      => trans('sales::visit.common.institute_new'),
			SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE => trans('sales::visit.common.institute_existing'),
		];
		$newInstitute = SalesVisitViewHelper::SELECT_OPTION_NEW_INSTITUTE;
		$existingInstitute = SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE;
		$instCategories = $this->instCategoryRepo->getList();
		$instInquiries = $this->instInquiryRepo->getList();
		$states = $this->statesRepo->getList();

		return View::make('sales::visit.create', compact('instCategories', 'instInquiries', 'states', 'instituteTypes', 'newInstitute', 'existingInstitute'));
	}

	/**
	 * Store a newly created sales visit
	 *
	 * @param  Visit\StoreRequest $request
	 *
	 * @return Response|RedirectResponse
	 * @throws Exception
	 */
	public function store( Visit\StoreRequest $request ) {
		DB::beginTransaction();
		try {
			$instInquiryId = null;
			$instInquiryData = [
				'institute_name'   => GeneralHelpers::clearParam($request->institute_name, PARAM_RAW_TRIMMED),
				'address'          => GeneralHelpers::clearParam($request->address, PARAM_RAW_TRIMMED),
				'city'             => GeneralHelpers::clearParam($request->city, PARAM_RAW_TRIMMED),
				'state_id'         => GeneralHelpers::clearParam($request->state_id, PARAM_RAW_TRIMMED),
				'student_strength' => GeneralHelpers::clearParam($request->student_strength, PARAM_RAW_TRIMMED),
				'inst_category_id' => GeneralHelpers::clearParam($request->inst_category_id, PARAM_RAW_TRIMMED),
				'user_ip'          => Helper::getIPAddress(),
				'inserted'         => Helper::datetimeToTimestamp(),
				'inserted_user'    => Session::get('user_id')
			];

			/* Create new Institute inquiry */
			if ( $request->institute_type == '1' ) {
				$instInquiryId = $this->instInquiryRepo->createInquiry($instInquiryData)['inst_inquiry_id'];
			} else {
				/* Update Existing Institute inquiry*/
				$instInquiryId = $request->inst_inquiry_id;
				$this->instInquiryRepo->updateInquiry($instInquiryData, $instInquiryId);
			}

			if ( $instInquiryId ) {
				$salesVisitData = [
					'visit_date'           => Helper::datetimeToTimestamp(),
					'contact_person'       => GeneralHelpers::clearParam($request->contact_person, PARAM_RAW_TRIMMED),
					'contact_person_desig' => GeneralHelpers::clearParam($request->contact_person_desig, PARAM_RAW_TRIMMED),
					'contact_person_phone' => GeneralHelpers::clearParam($request->contact_person_phone, PARAM_RAW_TRIMMED),
					'inst_inquiry_id'      => $instInquiryId,
					'remarks'              => GeneralHelpers::clearParam($request->remarks, PARAM_RAW_TRIMMED),
					'contact_person_email_id' => GeneralHelpers::clearParam($request->contact_person_email_id, PARAM_RAW_TRIMMED),
					'member_id'            => Session::get('member_id'),
					'inserted'             => Helper::datetimeToTimestamp(),
					'inserted_user'        => Session::get('user_id'),
					'user_ip'              => Helper::getIPAddress(),
					'device_type'          => 'BACKOFFICE',
				];

				/* Create sales visit entry */
				$salesVisitId = $this->salesVisitRepo->createSalesVisit($salesVisitData)['sales_visit_id'];

				if ( $salesVisitId ) {
					DBLog::save(LOG_MODULE_SALES_VISIT, $salesVisitId, 'insert', $request->getRequestUri(), Session::get('user_id'), $request->all());
				}
			}

			DB::commit();

			return Redirect::route('sales.visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {

			DB::rollBack();
			GeneralHelpers::logException($e);

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Show the form for editing the specified resource
	 *
	 * @param Visit\EditRequest $request
	 * @param  int              $salesVisitId Id of the sales visit entry
	 *
	 * @return View Returns Edit form of sales visit
	 */
	public function edit( Visit\EditRequest $request, $salesVisitId ) {
		$instituteTypes = [
			SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE => trans('sales::visit.common.institute_existing')
		];
		$salesVisit = $this->salesVisitRepo->getDetail($salesVisitId);
		$instInquiry = $this->instInquiryRepo->getDetail($salesVisit['inst_inquiry_id']);
		$instCategories = $this->instCategoryRepo->getList();
		$instInquiries = $this->instInquiryRepo->getList();

		$states = $this->statesRepo->getList();

		return View::make('sales::visit.edit', compact('salesVisitId', 'instCategories', 'instInquiries', 'states', 'salesVisit', 'instInquiry', 'instituteTypes'));
	}

	/**
	 * Update the specified resource in database
	 *
	 * @param Visit\UpdateRequest $request
	 * @param int                 $salesVisitId Id of sales visit entry
	 *
	 * @return Response|RedirectResponse  Returns to edit page in case of exception, or Search page in case of success
	 * @throws Exception
	 */
	public function update( Visit\UpdateRequest $request, $salesVisitId ) {
		DB::beginTransaction();
		$dbSalesVisit = $this->salesVisitRepo->getDetail($salesVisitId);
		try {
			/**
			 * Condition: different institute is selected in edit visit and old institute was acquired
			 * Action:
			 *        Remove acquisition detail of old inquiry
			 *        Remove acquisition status of visit
			 */
			if ( $dbSalesVisit['inst_inquiry_id'] != $request->get('inst_inquiry_id') && App::make(InstInquiryRepo::class)
			                                                                                ->isInstituteAcquired($dbSalesVisit['inst_inquiry_id'])
			) {

				$this->instInquiryRepo->removeInstituteAcquisition($dbSalesVisit['inst_inquiry_id'], Session::get('user_id'));
				$this->salesVisitRepo->updateAcquisition($salesVisitId, Session::get('user_id'), false);
			}

			/* Now Update PostBack institute details with sales visit details */
			$instInquiryData = [
				'address'          => GeneralHelpers::clearParam($request->address, PARAM_RAW_TRIMMED),
				'city'             => GeneralHelpers::clearParam($request->city, PARAM_RAW_TRIMMED),
				'state_id'         => GeneralHelpers::clearParam($request->state_id, PARAM_RAW_TRIMMED),
				'student_strength' => GeneralHelpers::clearParam($request->student_strength, PARAM_RAW_TRIMMED),
				'inst_category_id' => GeneralHelpers::clearParam($request->inst_category_id, PARAM_RAW_TRIMMED),
				'user_ip'          => Helper::getIPAddress(),
				'updated'          => Helper::datetimeToTimestamp(),
				'updated_user'     => Session::get('user_id')
			];

			$this->instInquiryRepo->updateInquiry($instInquiryData, GeneralHelpers::clearParam($request->inst_inquiry_id, PARAM_RAW_TRIMMED));

			$salesVisitData = [
				'visit_date'           => Helper::dateToTimestamp($request->visit_date),
				'contact_person'       => GeneralHelpers::clearParam($request->contact_person, PARAM_RAW_TRIMMED),
				'contact_person_desig' => GeneralHelpers::clearParam($request->contact_person_desig, PARAM_RAW_TRIMMED),
				'contact_person_phone' => GeneralHelpers::clearParam($request->contact_person_phone, PARAM_RAW_TRIMMED),
				'inst_inquiry_id'      => GeneralHelpers::clearParam($request->inst_inquiry_id, PARAM_RAW_TRIMMED),
				'remarks'              => GeneralHelpers::clearParam($request->remarks, PARAM_RAW_TRIMMED),
				'contact_person_email_id' => GeneralHelpers::clearParam($request->contact_person_email_id, PARAM_RAW_TRIMMED),
				'member_id'            => Session::get('member_id'),
				'inserted'             => Helper::datetimeToTimestamp(),
				'inserted_user'        => Session::get('user_id'),
				'user_ip'              => Helper::getIPAddress(),
				'device_type'          => 'BACKOFFICE',
			];

			$this->salesVisitRepo->updateSalesVisit($salesVisitData, $salesVisitId);
			DBLog::save(LOG_MODULE_ACQUISITION, $salesVisitId, 'update', $request->getRequestUri(), Session::get('user_id'), $request->all());
			DB::commit();

			return Redirect::route('sales.visit.index')->with('message', trans('shared::message.success.process'));

		} catch ( Exception $e ) {
			DB::rollBack();
			GeneralHelpers::logException($e, ['sales_visit_id' => $salesVisitId]);

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Remove sales visit entry
	 *
	 * @param Visit\DestroyRequest $destroyRequest
	 * @param  int                 $salesVisitId Id of sales visit entry
	 *
	 * @return RedirectResponse Returns to search page in case of success or fail
	 */
	public function destroy( Visit\DestroyRequest $destroyRequest, $salesVisitId ) {
		/*
            For Destroy
            1. If ACQ Done, then make blank acq status and institute id field in inst_inquiry table
               and set is_deleted = 1 in sales visit table
            2. If ACQ not Done, then set is_deleted = 1 in sales visit table
        */
		$salesVisit = $this->salesVisitRepo->getDetail(GeneralHelpers::clearParam($salesVisitId, PARAM_RAW_TRIMMED));
		try {

			DB::beginTransaction();
			if ( $salesVisit['acq_status'] == 1 ) {

				/* acq done - update is deleted field to 1 in sales_visit and remove
				   acq status and institute id from inst_inquiry */
				$acquisitionData = [
					'acq_status'        => 0,
					'converted_inst_id' => NULL
				];

				$updateInquiryStatus = $this->instInquiryRepo->updateInquiry($acquisitionData, $salesVisit['inst_inquiry_id']);

				if ( $updateInquiryStatus ) {
					DBLog::save(LOG_MODULE_ACQUISITION, $salesVisit['inst_inquiry_id'], 'delete', $destroyRequest->getRequestUri(), Session::get('user_id'), ['sales visit id' => $salesVisitId]);
				}
			}

			$updateSalesVisitStatus = $this->salesVisitRepo->updateSalesVisit(['is_deleted' => 1], $salesVisitId);
			if ( $updateSalesVisitStatus ) {
				DBLog::save(LOG_MODULE_SALES_VISIT, $salesVisitId, 'delete', $destroyRequest->getRequestUri(), Session::get('user_id'), ['inst inquiry id' => $salesVisit['inst_inquiry_id']]);
			}

			DB::commit();

			return Redirect::route('sales.visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {

			DB::rollBack();
			GeneralHelpers::logException($e, ['sales_visit_id' => $salesVisitId]);

			return Redirect::route('sales.visit.index')
			               ->withErrors(['sales_visit' => trans('shared::message.error.something_wrong')]);
		}
	}

	/**
	 * Show Acquisition page
	 *
	 * @param Visit\AcquisitionRequest $request
	 * @param int                      $salesVisitId Id of sales visit entry
	 *
	 * @return View
	 */
	public function acquisition( Visit\AcquisitionRequest $request, $salesVisitId ) {

		$salesVisitDetail = $this->salesVisitRepo->getInstituteAndCategoryDetail(GeneralHelpers::clearParam($salesVisitId, PARAM_RAW_TRIMMED));
		$selectedInstituteId = $selectedInstituteName = '';
		$institutes = [];

		/* If converted_inst_id found then show front-end flinnt institute as selected */
		if ( $salesVisitDetail['converted_inst_id'] ) {

			$instituteDetail = App::make(UserRepo::class)
			                      ->getAcquiredInstituteById($salesVisitDetail['converted_inst_id'], $withEmail = true);

			if ( $instituteDetail ) {
				$institutes = [GeneralHelpers::encode($instituteDetail['user_id']) => $instituteDetail['user_school_name']];
				$selectedInstituteName = $instituteDetail['user_school_name'];
			}
			$selectedInstituteId = $instituteDetail['user_id'];
		}

		return View::make('sales::visit.acquisition', compact('selectedInstituteName', 'salesVisitDetail', 'salesVisitId', 'institutes', 'selectedInstituteId'));
	}

	/**
	 * Acquisition Process
	 *
	 * @param Visit\AcquisitionDoRequest $request
	 * @param int                        $salesVisitId Id of sales visit entry
	 *
	 * @return RedirectResponse redirect back to institute call visit page
	 */
	public function acquisitionDo( Visit\AcquisitionDoRequest $request, $salesVisitId ) {
		try {
			DB::beginTransaction();
			$salesVisitDetail = $this->salesVisitRepo->getInstituteAndCategoryDetail(GeneralHelpers::clearParam($salesVisitId, PARAM_RAW_TRIMMED));

			if ( empty($salesVisitDetail['inst_inquiry_id']) ) {
				DB::rollBack();
				Log::error(trans('sales::visit.acquisition.error.acquisition'), ['salesVisitId' => $salesVisitId]);
				return Redirect::route('sales.visit.acquisition', [$salesVisitId])
				               ->withErrors(['sales_visit' => trans('shared::message.error.something_wrong')]);
			}

			if ( $request->has('remove_acq') && $request->input('remove_acq') == 1 ) {
				$log_action = 'remove';

				/* If remove_acq selected, then just flush acq_status in both tables and converted_inst_id
				   in sales_visit table */
				/* If remove_acq is selected and institute is changed in acq */

				$this->instInquiryRepo->removeInstituteAcquisition($salesVisitDetail['inst_inquiry_id'], Session::get('user_id'));
				$this->salesVisitRepo->updateAcquisition($salesVisitId, Session::get('user_id'), false);

			} else {
				$log_action = 'acq';

				/* If remove_acq not selected, then just update acq_status in both tables
				   and converted_inst_id in sales_visit table */

				$this->instInquiryRepo->acquireInstitute($salesVisitDetail['inst_inquiry_id'], GeneralHelpers::clearParam($request->input('user_id'), PARAM_RAW_TRIMMED), Session::get('member_id'), Session::get('user_id'));
				$this->salesVisitRepo->updateAcquisition($salesVisitId, Session::get('user_id'), true);
			}

			DBLog::save(LOG_MODULE_ACQUISITION, $salesVisitDetail['inst_inquiry_id'], $log_action, $request->getRequestUri(), Session::get('user_id'), $request->all());
			DBLog::save(LOG_MODULE_SALES_VISIT, $salesVisitId, $log_action, $request->getRequestUri(), Session::get('user_id'), ['inst inquiry id' => $salesVisitDetail['inst_inquiry_id']]);

			DB::commit();

			return Redirect::route('sales.visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			DB::rollBack();
			GeneralHelpers::logException($e, ['sales_visit_id' => $salesVisitId]);

			return Redirect::route('sales.visit.acquisition', [$salesVisitId])
			               ->withErrors(['sales_visit' => trans('shared::message.error.something_wrong')]);
		}
	}
}
