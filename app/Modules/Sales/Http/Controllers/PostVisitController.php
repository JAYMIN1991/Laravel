<?php

namespace App\Modules\Sales\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Sales\Repositories\Contracts\AfterSalesVisitRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use Flinnt\Core\DBLog\Facade\DBLog;
use Helper;
use Illuminate\Http\RedirectResponse;
use League\Flysystem\Exception;
use Redirect;
use Response;
use Session;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use View;
use App\Modules\Sales\Http\Requests\PostVisit;

/**
 * Class PostVisitController
 * @package App\Modules\Sales\Http\Controllers
 */
class PostVisitController extends Controller {

	/**
	 * @var SalesTeamRepo $salesTeamRepo Repository of SalesTeam
	 */
	protected $salesTeamRepo;

	/**
	 * @var AfterSalesVisitRepo $afterSalesVisitRepo Repository of AfterSalesVisit
	 */
	protected $afterSalesVisitRepo;

	/**
	 * PostVisitController constructor
	 *
	 * @param SalesTeamRepo       $salesTeamRepo
	 * @param AfterSalesVisitRepo $afterSalesVisitRepo
	 */
	public function __construct( SalesTeamRepo $salesTeamRepo, AfterSalesVisitRepo $afterSalesVisitRepo ) {
		$this->salesTeamRepo = $salesTeamRepo;
		$this->afterSalesVisitRepo = $afterSalesVisitRepo;
	}

	/**
	 * Display a listing of after sales visit
	 *
	 * @param PostVisit\IndexRequest $request index request object of after sales visit
	 *
	 * @return \Illuminate\Http\Response|RedirectResponse
	 */
	public function index( PostVisit\IndexRequest $request ) {

		if ( $request->exists('button_export') ) {
			$postSalesVisits = $this->afterSalesVisitRepo->searchAfterSalesVisit(false);

			if ( $postSalesVisits->isEmpty() ) {
				return Redirect::back()->withErrors(trans('shared::message.error.nothing_to_export'));
			}

			$exportColumnNames = [
				'ref_by'                  => trans('sales::post-visit.common.visit_by'),
				'visit_date'              => trans('sales::post-visit.common.visit_date'),
				'institute_name'          => trans('sales::post-visit.common.institute_name'),
				'contact_person'          => trans('sales::post-visit.common.contact_person'),
				'contact_person_desig'    => trans('sales::post-visit.common.designation'),
				'contact_person_phone'    => trans('sales::post-visit.common.phone'),
				'remarks'                 => trans('sales::post-visit.common.remarks'),
				'contact_person_email_id' => trans('sales::post-visit.common.email')
			];
			$fileName = 'after_sales_visit_list';
			GeneralHelpers::exportToExcel($exportColumnNames, $postSalesVisits->all(), $fileName);
		}

		$visitDateDefault = (string) Helper::getDate(trans('shared::config.output_date_format'));
		$visitBy = $this->salesTeamRepo->getListByUserId(Session::get('user_id'));
		$institutes = [];
		$instUserId = GeneralHelpers::clearParam($request->institute, PARAM_RAW_TRIMMED);
		$postSalesVisits = $this->afterSalesVisitRepo->searchAfterSalesVisit(true)->appends($request->except('page'));

		if ( ! empty($instUserId) ) {
			$institutes = App::make(UserMasterRepo::class)->getInstituteByOwnerId($instUserId);

			if ( $institutes ) {
				$institutes = [GeneralHelpers::encode($institutes['user_id']) => $institutes['user_school_name']];
			}
		}

		return View::make('sales::post-visit.index', compact('institutes', 'visitDateDefault', 'visitBy', 'postSalesVisits'));
	}

	/**
	 * Show the form for creating a new sales visit
	 *
	 * @return View
	 */
	public function create() {
		$visitDateDefault = (string) Helper::getDate(trans('shared::config.output_date_format'));

		return View::make('sales::post-visit.create', compact('visitDateDefault'));
	}

	/**
	 * Store a newly created after sales visit entry
	 *
	 * @param PostVisit\StoreRequest $request store request object of after sales visit
	 *
	 * @return Response|RedirectResponse
	 */
	public function store( PostVisit\StoreRequest $request ) {
		try {
			$postVisitData = [
				'visit_date'              => Helper::dateToTimestamp(GeneralHelpers::clearParam($request->visit_date, PARAM_RAW_TRIMMED)),
				'contact_person'          => GeneralHelpers::clearParam($request->contact_person, PARAM_RAW_TRIMMED),
				'contact_person_desig'    => GeneralHelpers::clearParam($request->contact_person_desig, PARAM_RAW_TRIMMED),
				'contact_person_phone'    => GeneralHelpers::clearParam($request->contact_person_phone, PARAM_RAW_TRIMMED),
				'inst_user_id'            => GeneralHelpers::clearParam($request->inst_user_id, PARAM_RAW_TRIMMED),
				'remarks'                 => GeneralHelpers::clearParam($request->remarks, PARAM_RAW_TRIMMED),
				'contact_person_email_id' => GeneralHelpers::clearParam($request->contact_person_email_id, PARAM_RAW_TRIMMED),
				'inserted'                => Helper::dateToTimestamp(),
				'inserted_user'           => Session::get('user_id'),
				'user_ip'                 => Helper::getIPAddress(),
				'device_type'             => 'BACKOFFICE'
			];

			$afterSalesVisitId = $this->afterSalesVisitRepo->createAfterSalesVisit($postVisitData)['after_sales_visit_id'];
			DBLog::save(LOG_MODULE_AFTER_SALES_VISIT, $afterSalesVisitId, 'insert', $request->getRequestUri(), $request->all());

			return Redirect::route('sales.post-visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			GeneralHelpers::logException($e, $request->all());

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Show the form for editing after sales visit entry
	 *
	 * @param PostVisit\EditRequest $request           edit request object of after sales visit
	 * @param  int                  $afterSalesVisitId id of after sales visit
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( PostVisit\EditRequest $request, $afterSalesVisitId ) {
		$visitDateDefault = (string) Helper::getDate(trans('shared::config.output_date_format'));
		$afterSalesVisitDetail = $this->afterSalesVisitRepo->getAfterSalesVisitDetail($afterSalesVisitId);
		$institutes = [];

		if ( $afterSalesVisitDetail ) {
			$institutes = App::make(UserMasterRepo::class)
			                 ->getInstituteByOwnerId($afterSalesVisitDetail['inst_user_id']);

			if ( $institutes ) {
				$institutes = [GeneralHelpers::encode($institutes['user_id']) => $institutes['user_school_name']];
			}
		}

		return View::make('sales::post-visit.edit', compact('visitDateDefault', 'afterSalesVisitDetail', 'institutes'));
	}

	/**
	 * Update after sales visit entry
	 *
	 * @param PostVisit\UpdateRequest $request           Update request object of after sales visit
	 * @param  int                    $afterSalesVisitId Id of after sales visit
	 *
	 * @return \Illuminate\Http\RedirectResponse Redirect back to edit of fail, on success redirect to search page
	 */
	public function update( PostVisit\UpdateRequest $request, $afterSalesVisitId ) {
		try {
			$postVisitDetail = [
				'visit_date'              => Helper::dateToTimestamp(GeneralHelpers::clearParam($request->visit_date, PARAM_RAW_TRIMMED)),
				'contact_person'          => GeneralHelpers::clearParam($request->contact_person, PARAM_RAW_TRIMMED),
				'contact_person_desig'    => GeneralHelpers::clearParam($request->contact_person_desig, PARAM_RAW_TRIMMED),
				'contact_person_phone'    => GeneralHelpers::clearParam($request->contact_person_phone, PARAM_RAW_TRIMMED),
				'inst_user_id'            => GeneralHelpers::clearParam($request->inst_user_id, PARAM_RAW_TRIMMED),
				'remarks'                 => GeneralHelpers::clearParam($request->remarks, PARAM_RAW_TRIMMED),
				'contact_person_email_id' => GeneralHelpers::clearParam($request->contact_person_email_id, PARAM_RAW_TRIMMED),
				'updated'                 => Helper::datetimeToTimestamp(),
				'updated_user'            => Session::get('user_id'),
				'user_ip'                 => Helper::getIPAddress(),
				'device_type'             => 'BACKOFFICE'
			];
			$afterSalesVisitId = $this->afterSalesVisitRepo->updateAfterSalesVisit($postVisitDetail, $afterSalesVisitId)['after_sales_visit_id'];
			DBLog::save(LOG_MODULE_AFTER_SALES_VISIT, $afterSalesVisitId, 'update', $request->getRequestUri(), 'after_sales_visit_id = ' . $afterSalesVisitId);

			return Redirect::route('sales.post-visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			GeneralHelpers::logException($e, array_merge($request->all(), ['after_sales_visit_id' => $afterSalesVisitId]));

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Remove after sales visit entry
	 *
	 * @param PostVisit\DestroyRequest $request           Destroy request object of after sales visit
	 * @param  int                     $afterSalesVisitId Id of after sales visit
	 *
	 * @return \Illuminate\Http\RedirectResponse Redirect back to search page
	 */
	public function destroy( PostVisit\DestroyRequest $request, $afterSalesVisitId ) {
		try {
			$this->afterSalesVisitRepo->deleteAfterSalesVisit($afterSalesVisitId);
			DBLog::save(LOG_MODULE_AFTER_SALES_VISIT, $afterSalesVisitId, 'delete', $request->getRequestUri(), Session::get('user_id'), $request->all());

			return Redirect::route('sales.post-visit.index')->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			GeneralHelpers::logException($e, ['sales_visit_id' => $afterSalesVisitId], trans('sales::post-visit.destroy.error'));

			return Redirect::route('sales.post-visit.index')
			               ->withErrors(['after_sales_visit' => trans('shared::message.error.something_wrong')]);
		}
	}
}
