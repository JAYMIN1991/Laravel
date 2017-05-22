<?php

namespace App\Modules\Account\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Account\Http\Requests\commission\CreateRequest;
use App\Modules\Account\Http\Requests\commission\IndexRequest;
use App\Modules\Account\Http\Requests\commission\UpdateRequest;
use App\Modules\Account\Repositories\Contracts\CourseTypeRepo;
use App\Modules\Account\Repositories\Contracts\PayCommissionsRepo;
use App\Modules\Account\Repositories\Contracts\UserCommissionDiscountRepo;
use App\Modules\Shared\Misc\UserCommissionRangeListViewHelper;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Helper;
use Illuminate\Routing\Controller;
use Session;
use View;

/**
 * User commission page CRUD method
 * @see UserCommissionListController
 */
class UserCommissionListController extends Controller {

	protected $userCommissionListRepo;
	protected $courseTypeRepo;
	protected $request;

	/**
	 * UserCommissionListController constructor.
	 *
	 * @param UserCommissionDiscountRepo $userCommissionListRepo
	 * @param CourseTypeRepo             $courseTypeRepo
	 */
	public function __construct( UserCommissionDiscountRepo $userCommissionListRepo, CourseTypeRepo $courseTypeRepo ) {
		$this->userCommissionListRepo = $userCommissionListRepo;
		$this->courseTypeRepo = $courseTypeRepo;
	}

	/**
	 * @param IndexRequest $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index( IndexRequest $request ) {
		// collection for course type like: time bound etc..
		$courseTypeOptions = App::make(CourseTypeRepo::class)->getCourseTypeList();

		// List of comparision operator to compare commission value
		$commissionRangeOptions = array(
			UserCommissionRangeListViewHelper::SELECT_COMMISSION_RANGE_EQUAL         => trans('account::user-commission.index.equal'),
			UserCommissionRangeListViewHelper::SELECT_COMMISSION_RANGE_LESS_THEN     => trans('account::user-commission.index.less_then'),
			UserCommissionRangeListViewHelper::SELECT_COMMISSION_RANGE_GREATER_THEN  => trans('account::user-commission.index.greater_then'),
			UserCommissionRangeListViewHelper::SELECT_COMMISSION_RANGE_LESS_EQUAL    => trans('account::user-commission.index.less_equal'),
			UserCommissionRangeListViewHelper::SELECT_COMMISSION_RANGE_GREATER_EQUAL => trans('account::user-commission.index.greater_equal')
		);

		// Get institute name when institute id is got
		if ( $request->has('institute_id') ) {
			$instituteName = App::make(UserMasterRepo::class)
			                    ->getInstituteByOwnerId($request->get('dec_institute_id'))['user_school_name'];
		}

		// collection Yes and no drop-down for applicable option
		$selectApplicableOptions = array(
			ViewHelper::SELECT_OPTION_VALUE_YES => trans('account::user-commission.common.yes'),
			ViewHelper::SELECT_OPTION_VALUE_NO  => trans('account::user-commission.common.no')
		);
		// Get user commission data after search result
		$commissionList = $this->userCommissionListRepo->getUserCommissionList(true)
		                                               ->appends($request->except('page', 'dec_institute_id'));

		foreach ( $commissionList as $key => $commissionDetails ) {
			if ( $commissionDetails['comm_discount_id'] ) {
				$commissionDetails['comm_discount_id'] = GeneralHelpers::encode($commissionDetails['comm_discount_id']);
			}
			$commissionList[$key] = $commissionDetails;
		}

		// Insert DB log
		DBLog::save(LOG_MODULE_USER_COMMISSION, $commissionList['comm_discount_id'], 'Search', $request->getRequestUri(), Session::get('user_id'), $commissionList);

		return View::make('account::commission.index', compact('courseTypeOptions', 'commissionRangeOptions', 'instituteName', 'selectApplicableOptions', 'commissionList'));
	}

	/**
	 * Course type list when create new user commission
	 * @return \Illuminate\Contracts\View\View
	 */
	public function create() {
		$courseTypeOptions = App::make(CourseTypeRepo::class)
		                        ->getCourseTypeList()
		                        ->prepend('-- ' . trans('account::user-commission.common.any') . ' --', 0);

		return View::make('account::commission.create', compact('courseTypeOptions'));
	}

	/**
	 * Insert user commission discount records to DB
	 *
	 * @param CreateRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store( CreateRequest $request ) {
		// get commission Id from pay_commission_table
		$commissionId = $this->userCommissionListRepo->getCommissionByCourseTypeId($request->input('course_type'))[0];
		$commissionData['commission_id'] = $commissionId;

		if ( $request->input('course_type') == 2 ) {
			$commissionData['actual_perc'] = USER_COMMISSION_TIME_OUT;
		} else {
			$commissionData['actual_perc'] = USER_COMMISSION_SELF_PACED;
		}

		$commissionData['applicable_perc'] = $request->input('apply_commission');
		$commissionData['user_id'] = GeneralHelpers::decode($request->input('institute_id'));

		if ( $request->input('not_applicable') == '1' ) {
			$commissionData['is_applicable'] = 1;
		} else {
			$commissionData['is_applicable'] = 0;
		}

		$commissionData['inserted_dt'] = Helper::datetimeToTimestamp();
		$commissionData['bkoff_user_id'] = Session::get('user_id');
		$checkExist = $this->userCommissionListRepo->createOrUpdateCommissionData($commissionData, array(
			'commission_id',
			'user_id',
			'is_applicable'
		));

		// Add DB log to insert to update operation
		if ( $checkExist == true ) {
			if ( $checkExist['operation'] == 'insert' ) {
				DBLog::save(LOG_MODULE_USER_COMMISSION, $checkExist['comm_discount_id'], 'insert', $request->getRequestUri(), Session::get('user_id'), $commissionData);
			} else {
				DBLog::save(LOG_MODULE_USER_COMMISSION, $checkExist['comm_discount_id'], 'update', $request->getRequestUri(), Session::get('user_id'), $commissionData);
			}

			return redirect()
				->route('account.user-commission.search')
				->with('message', trans('shared::message.success.process'));
		} else {
			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}

	}

	/**
	 * Load existing data when edit page
	 *
	 * @param $requestId
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function edit( $requestId ) {
		// Encode request id for security reason
		if ( $requestId ) {
			$requestId = GeneralHelpers::decode($requestId);
		}

		// Get All user commission details from ID
		$details = $this->userCommissionListRepo->getUserCommissionDetails($requestId);
		$details['comm_discount_id'] = GeneralHelpers::encode($details['comm_discount_id']);
		// Get Select course type using commission Id
		$courseTypeId = App::make(PayCommissionsRepo::class)->getCourseTypeByCommissionId($details['commission_id']);
		$courseTypeId = $courseTypeId['course_type_id'];
		// Get institute Name using user Id
		$instituteName = App::make(UserMasterRepo::class)->getInstituteByOwnerId($details['user_id']);
		$instituteName['user_id'] = GeneralHelpers::encode($instituteName['user_id']);
		$instituteName = collect([$instituteName['user_id'] => $instituteName['user_school_name']]);
		$courseTypeCollection = $this->courseTypeRepo->getCourseTypeList()
		                                             ->prepend('-- ' . trans('account::user-commission.common.any') . ' --', ViewHelper::SELECT_OPTION_VALUE_PLACEHOLDER);
		$data = compact('courseTypeId', 'courseTypeCollection', 'instituteName', 'details');

		return View::make('account::commission.edit', $data);
	}

	/**
	 * Update user commission discount by commission discount id
	 *
	 * @param UpdateRequest $request
	 * @param               $commDiscountId
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateRequest $request, $commDiscountId ) {
		// Decode commission Discount id
		if ( $commDiscountId ) {
			$commDiscountId = GeneralHelpers::decode($commDiscountId);
		}

		// Get commission id using course type
		$commissionId = $this->userCommissionListRepo->getCommissionByCourseTypeId($request->input('course_type'))[0];

		if ( $commissionId ) {
			$commissionData['commission_id'] = $commissionId;

			if ( $request->input('course_type') == 2 ) {
				$commissionData['actual_perc'] = USER_COMMISSION_TIME_OUT;
			} else {
				$commissionData['actual_perc'] = USER_COMMISSION_SELF_PACED;
			}
			$commissionData['applicable_perc'] = $request->input('apply_commission');
			$commissionData['user_id'] = GeneralHelpers::decode($request->input('institute_id'));

			if ( $request->input('not_applicable') == '1' ) {
				$commissionData['is_applicable'] = 1;
			} else {
				$commissionData['is_applicable'] = 0;
			}

			$commissionData['bkoff_user_id'] = Session::get('user_id');
			// Update user commission date using comm_discount_id
			$UpdateCommission = $this->userCommissionListRepo->updateCommissionData($commissionData, $commDiscountId);

			if ( $UpdateCommission == true ) {
				DBLog::save(LOG_MODULE_USER_COMMISSION, $UpdateCommission['comm_discount_id'], 'Update', $request->getRequestUri(), Session::get('user_id'), $commissionData);

				return redirect()
					->route('account.user-commission.search')
					->with('message', trans('shared::message.success.process'));
			} else {
				return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
			}
		} else {
			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}
}
