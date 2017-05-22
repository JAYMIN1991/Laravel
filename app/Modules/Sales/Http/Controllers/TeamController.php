<?php

namespace App\Modules\Sales\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Sales\Http\Requests\Team as Team;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Shared\Misc\ViewHelper;
use DB;
use DBLog;
use Exception;
use Psy\Util\Json;
use Session;
use View;

/**
 * Controller for CRUD operation on Sales Team
 * Class SalesTeamController
 * @package App\Modules\Sales\Http\Controllers
 */
class TeamController extends Controller {

	/**
	 * @var SalesTeamRepo $salesTeamRepo
	 */
	protected $salesTeamRepo;

	/**
	 * SalesTeamController constructor
	 *
	 * @param SalesTeamRepo $salesTeamRepo
	 */
	public function __construct( SalesTeamRepo $salesTeamRepo ) {
		$this->salesTeamRepo = $salesTeamRepo;
	}

	/**
	 * Listing of the Sales Team
	 *
	 * @param Team\IndexRequest $request
	 *
	 * @return View|Json
	 */
	public function index( Team\IndexRequest $request ) {
		$isLeft = [
			ViewHelper::SELECT_OPTION_VALUE_ANY =>  '-- '.trans('shared::common.dropdown.any').' --',
			ViewHelper::SELECT_OPTION_VALUE_YES => trans('shared::common.dropdown.yes'),
			ViewHelper::SELECT_OPTION_VALUE_NO  => trans('shared::common.dropdown.no')
		];
		$reportedTo = $this->salesTeamRepo->getListForReportedTo();
		$salesMembers = $this->salesTeamRepo->search()->appends($request->except('page'));
		DBLog::save(LOG_MODULE_SALES_TEAM, NULL, 'search', $request->getRequestUri(), Session::get('user_id'));

		return view('sales::team.index', compact('salesMembers', 'isLeft', 'reportedTo'));
	}

	/**
	 * Show the form for creating a new sale team member
	 *
	 * @return View
	 */
	public function create() {
		$reportedTo = $this->salesTeamRepo->getListForReportedTo();

		return View::make('sales::team.create', compact('reportedTo'));
	}

	/**
	 * Store a newly created resource in storage
	 *
	 * @param  Team\CreateRequest $request
	 *
	 * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
	 */
	public function store( Team\CreateRequest $request ) {
		DB::beginTransaction();
		try {
			$memberData = $request->only(['first_name', 'last_name', 'city_name']);

			if ( $request->input('is_left') == '1' ) {
				$memberData['is_left'] = 1;
			}

			if ( $request->has('parent_member_id') && is_numeric($request->input('parent_member_id')) ) {
				$memberData['parent_member_id'] = $request->input('parent_member_id');
			}

			$createdMember = $this->salesTeamRepo->createMember($memberData);

			DB::commit();
			DBLog::save(LOG_MODULE_SALES_TEAM, $createdMember['member_id'], 'insert', $request->getRequestUri(), Session::get('user_id'), $memberData);

			return redirect()->route('sales.team.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {

			/* Rollback the transaction and log error */
			DB::rollBack();
			GeneralHelpers::logException($e);

			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Show the form for editing the specified resource
	 *
	 * @param  int $memberId id of edited member
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( $memberId ) {
		$memberDetail = $this->salesTeamRepo->getMemberDetail($memberId);

		if ( $memberDetail ) {
			$reportedTo = $this->salesTeamRepo->getListForReportedTo();

			return View::make('sales::team.edit', compact('reportedTo', 'memberDetail'));
		} else {
			try {
				App::abort(400, trans('shared::message.error.not_found'));

			} catch ( Exception $e ) {
				GeneralHelpers::logExceptionAndHalt($e, ['member_id' => $memberId]);
			}
		}
	}

	/**
	 * Update the specified resource in storage
	 *
	 * @param  Team\UpdateRequest $request
	 * @param  string        $memberId
	 *
	 * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
	 */
	public function update( Team\UpdateRequest $request, $memberId ) {

		/* Check if Valid member id is supplied before trying to update */
		$memberDetail = $this->salesTeamRepo->getMemberDetail($memberId);
		if ( ! $memberDetail ) {
			try {
				App::abort(400, trans('shared::message.error.not_found'));
			} catch ( Exception $e ) {
				GeneralHelpers::logExceptionAndHalt($e, ['member_id' => $memberId]);
			}
		}

		DB::beginTransaction();
		try {
			$memberData = $request->only(['first_name', 'last_name', 'city_name']);
			$memberData['is_left'] = $request->input('is_left') == '1' ? 1 : 0;
			$memberData['parent_member_id'] = GeneralHelpers::isNull($request->input('parent_member_id')) ? 0 : (int) $request->input('parent_member_id');

			$memberDetail = $this->salesTeamRepo->updateMember($memberData, $memberId);
			$message = trans('shared::message.success.process');

			DB::commit();
			DBLog::save(LOG_MODULE_SALES_TEAM, $memberDetail['member_id'], 'update', $request->getRequestUri(), Session::get('user_id'), $memberData);

			return redirect()->route('sales.team.index')->with('message', $message);
		} catch ( Exception $e ) {

			DB::rollBack();
			GeneralHelpers::logException($e);

			return redirect()->back()->withErrors(trans('shared::message.common.error.something_wrong'))->withInput();
		}
	}
}
