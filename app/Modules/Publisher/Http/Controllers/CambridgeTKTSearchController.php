<?php

namespace App\Modules\Publisher\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Publisher\Http\Requests\CambridgeTKTExamStoreRequest;
use App\Modules\Publisher\Http\Requests\UpdateCombridgeTKTExamRequest;
use App\Modules\Publisher\Repositories\Contracts\CambridgeTKTModuleListRepo;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Misc\CambridgeModuleTypeViewHelper;
use DBLog;
use Helper;
use Illuminate\Http\Request;
use Session;
use View;

/**
 * Class CambridgeTKTSearchController
 * @package App\Modules\Publisher\Http\Controllers
 */
class CambridgeTKTSearchController extends Controller {

	/**
	 * @var
	 */
	protected $request;

	/**
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index( Request $request ) {
		// Get module list collection
		$moduleListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                        ->getCambridgeTKTExamModuleList()
		                        ->pluck('test_name', 'test_name');

		// Get city type list
		$cityListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                      ->getCambridgeTKTExamCityList()
		                      ->pluck('test_location', 'test_location');

		// Get set search request
		if ( $request->has('btnsearch') ) {
			$cambridgeTKTExamResult = App::make(CambridgeTKTModuleListRepo::class)
			                             ->getCambridgeTKTExamSearch(true)
			                             ->appends($request->except('page'));

			/* @var \Illuminate\Pagination\LengthAwarePaginator $cambridgeTKTExamResult */
			if ( ! $cambridgeTKTExamResult->isEmpty() ) {
				$cambridgeTKTExamResult = GeneralHelpers::encryptColumns($cambridgeTKTExamResult, 'id');
			}
			// DB log for search tracking
			DBLog::save(LOG_MODULE_CAMBRIDGE_TKT_EXAM, $cambridgeTKTExamResult['id'], 'Search', $request->getRequestUri(), Session::get('user_id'), $cambridgeTKTExamResult);
		}
		$cambridgeTKTExamData = compact('moduleListOptions', 'cityListOptions', 'cambridgeTKTExamResult');

		return View::make('publisher::tkt.index', $cambridgeTKTExamData);
	}

	// Create request controller for cambridge TKT exam
	public function create() {
		// Get module type list
		$moduleListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                        ->getCambridgeTKTExamModuleList()
		                        ->pluck('test_name', 'test_name');

		// Get city list options
		$cityListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                      ->getCambridgeTKTExamCityList()
		                      ->pluck('test_location', 'test_location');

		// Get module type list
		$moduleTypeOptions = array(
			CambridgeModuleTypeViewHelper::SELECT_OPTION_VALUE_ANY => '-- ' . trans('publisher::cambridge-tkt-exam.common.any') . ' --',
			CambridgeModuleTypeViewHelper::EXISTING_MODULE         => trans('publisher::cambridge-tkt-exam.create.existing_module'),
			CambridgeModuleTypeViewHelper::NEW_MODULE              => trans('publisher::cambridge-tkt-exam.create.new_module')
		);

		// Get city type list
		$cityTypeOptions = array(
			CambridgeModuleTypeViewHelper::SELECT_OPTION_VALUE_ANY => '-- ' . trans('publisher::cambridge-tkt-exam.common.any') . ' --',
			CambridgeModuleTypeViewHelper::EXISTING_MODULE         => trans('publisher::cambridge-tkt-exam.create.existing_city'),
			CambridgeModuleTypeViewHelper::NEW_MODULE              => trans('publisher::cambridge-tkt-exam.create.new_city')
		);

		$cambridgeTKTExamDat = compact('moduleListOptions', 'cityListOptions', 'cambridgeTKTExamResult', 'moduleTypeOptions', 'cityTypeOptions');

		return View::make('publisher::tkt.create', $cambridgeTKTExamDat);
	}

	// Store new records to DB
	public function store( CambridgeTKTExamStoreRequest $request ) {

		if ( $request->input('module_type') == 1 ) {
			$CambridgeTKTExamData['test_name'] = $request->input('module_list_id');
		} else {
			$CambridgeTKTExamData['test_name'] = $request->input('new_module');
		}

		// Check if existing city is selected or new city added
		if ( $request->input('city_type') == 1 ) {
			$CambridgeTKTExamData['test_location'] = $request->input('city_name');
		} else {
			$CambridgeTKTExamData['test_location'] = $request->input('new_city');
		}

		$CambridgeTKTExamData['test_date'] = (string) Helper::getDate('d M Y', $request->input('date'), 'd M Y');
		$CambridgeTKTExamData['test_dt'] = (string) Helper::getDate('Y-m-d', $request->input('date'), 'd M Y');
		$CambridgeTKTExamData['test_url'] = $request->input('url');
		$CambridgeTKTExamData['is_active'] = '1';
		// Insert cambridge TKT exam data
		$CambridgeTKTExamDataInsert = App::make(CambridgeTKTModuleListRepo::class)
		                                 ->insertCambridgeTKTExamData($CambridgeTKTExamData);

		// Insert DB log
		if ( $CambridgeTKTExamDataInsert ) {
			DBLog::save(LOG_MODULE_CAMBRIDGE_TKT_EXAM, $CambridgeTKTExamDataInsert['id'], 'insert', $request->getRequestUri(), Session::get('user_id'), $CambridgeTKTExamData);
		}

		return redirect()
			->route('publisher.cambridge.tkt.search')
			->with('message', trans('shared::message.success.process'));
	}

	// Edit controller
	public function edit( $cambridgeTKTExamId ) {

		// Get module type list
		$moduleTypeOptions = array(
			CambridgeModuleTypeViewHelper::EXISTING_MODULE => trans('publisher::cambridge-tkt-exam.create.existing_module'),
			CambridgeModuleTypeViewHelper::NEW_MODULE      => trans('publisher::cambridge-tkt-exam.create.new_module')
		);

		// Get city type list
		$cityTypeOptions = array(
			CambridgeModuleTypeViewHelper::EXISTING_MODULE => trans('publisher::cambridge-tkt-exam.create.existing_city'),
			CambridgeModuleTypeViewHelper::NEW_MODULE      => trans('publisher::cambridge-tkt-exam.create.new_city')
		);

		// Get module list
		$moduleListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                        ->getCambridgeTKTExamModuleList()
		                        ->pluck('test_name', 'test_name');

		// Get city list
		$cityListOptions = App::make(CambridgeTKTModuleListRepo::class)
		                      ->getCambridgeTKTExamCityList()
		                      ->pluck('test_location', 'test_location');

		if ( $cambridgeTKTExamId ) {
			$cambridgeTKTExamId = GeneralHelpers::decode($cambridgeTKTExamId);

			$tktExamRecords = App::make(CambridgeTKTModuleListRepo::class)
			                              ->getCambridgeTKTExamData($cambridgeTKTExamId);

		}

		$cambridgeTKTExamData = compact('tktExamRecords', 'moduleListOptions', 'cityListOptions', 'moduleTypeOptions', 'cityTypeOptions');

		return View::make('publisher::tkt.edit', $cambridgeTKTExamData);
	}

	/**
	 * @param \App\Modules\Publisher\Http\Requests\UpdateCombridgeTKTExamRequest $request
	 * @param                                                                    $CambridgeTKTExamId
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( UpdateCombridgeTKTExamRequest $request, $CambridgeTKTExamId ) {

		$cambridgeTKTExamData['test_name'] = ($request['module_type'] == '1') ? $request['module_list_id'] : $request['new_module'];
		$cambridgeTKTExamData['test_location'] = ($request['city_type'] == '1') ? $request['city_name'] : $request['new_city'];
		$cambridgeTKTExamData['test_date'] = (string) Helper::getDate('d M Y', $request->input('date'), 'd M Y');
		$cambridgeTKTExamData['test_dt'] = (string) Helper::getDate('Y-m-d', $request->input('date'), 'd M Y');
		$cambridgeTKTExamData['test_url'] = $request['url'];
		$cambridgeTKTExamData['is_active'] = '1';

		// Save updated data to respective id
		$updateResult = App::make(CambridgeTKTModuleListRepo::class)
		                                    ->CambridgeTKTExamDataUpdate($cambridgeTKTExamData, $CambridgeTKTExamId);

		// Save updated data to respective id log
		if ( $updateResult ) {
			DBLog::save(LOG_MODULE_CAMBRIDGE_TKT_EXAM, $updateResult['id'], 'update', $request->getRequestUri(), Session::get('user_id'), $cambridgeTKTExamData);
		}

		return redirect()
			->route('publisher.cambridge.tkt.search')
			->with('message', trans('shared::message.success.process'));
	}
}
