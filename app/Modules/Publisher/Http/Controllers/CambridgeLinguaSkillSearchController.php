<?php

namespace App\Modules\Publisher\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Common\PermissionHelpers;
use App\Modules\Location\Repositories\Contracts\StatesRepo;
use App\Http\Controllers\Controller;
use App\Modules\Publisher\Repositories\Contracts\CambridgeLinguaSkillSearchRepo;
use DBLog;
use Illuminate\Http\Request;
use Helper;
use Session;
use View;

/**
 * Class CambridgeLinguaSkillSearchController
 * @package App\Modules\Publisher\Http\Controllers
 */
class CambridgeLinguaSkillSearchController extends Controller {

	/**
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index( Request $request ) {
		// Check current user have export functionality access
		$canExport = PermissionHelpers::canExport(Session::get('user_id'));

		// Get institute Type options
		$instituteTypeOptions = App::make(CambridgeLinguaSkillSearchRepo::class)->getInstituteTypeListLinguaSkill();

		// Get state options
		$stateOptions = App::make(StatesRepo::class)->getList();

		// Get city options
		$cityOptions = App::make(CambridgeLinguaSkillSearchRepo::class)->getCityListLinguaSkillSearch();

		// Get candidate Range options

		$candidateRangeOptions = App::make(CambridgeLinguaSkillSearchRepo::class)->getRangeLinguaSkillCandidate();

		if ( $request->has('btnsearch') && $request->input('btnsearch') == 'submit' ) {
			$CambridgeLinguaSkillSearchResult = App::make(CambridgeLinguaSkillSearchRepo::class)
			                                       ->getLinguaSkillSearchResult(true)
			                                       ->appends($request->except('page'));

			if ( ! $CambridgeLinguaSkillSearchResult->isEmpty() ) {
				foreach ( $CambridgeLinguaSkillSearchResult as $key => $value ) {
					if ( $value['reg_date'] ) {
						$value['reg_date'] = (string) Helper::timestempToDate($value['reg_date'], 'd M Y');
					}
					$CambridgeLinguaSkillSearchResult[$key] = $value;
				}
			}
			// DB log for search
			DBLog::save(LOG_MODULE_LINGUA_SEARCH, $request->input('btnsearch'), 'Search', $request->getRequestUri(), Session::get('user_id'), $CambridgeLinguaSkillSearchResult);
		}

		if ( $canExport && $request->has('btnexport') && $request->input('btnexport') == 'export' ) {
			$cambridgeLinguaSkillReport = App::make(CambridgeLinguaSkillSearchRepo::class)
			                                 ->getLinguaSkillSearchResult(false);

			$exportColumnNames = [
				'inst_name'            => 'Institute Name',
				'type_text'            => 'Inst Type',
				'contact_address'      => 'Address',
				'contact_city'         => 'Contact City',
				'state_name'           => 'State',
				'contact_phone'        => 'Phone',
				'contact_email'        => 'Email',
				'contact_person'       => 'Contact Person',
				'contact_person_phone' => 'contact phone',
				'designation'          => 'Designation',
				'range_text'           => 'Range',
				'exam_date'            => 'Exam Date',
				'reg_date'             => 'Registration Date'
			];
			// Insert DB log for export tracking
			DBLog::save(LOG_MODULE_LINGUA_SEARCH, $request->input('btnexport'), 'Export', $request->getRequestUri(), Session::get('user_id'), $cambridgeLinguaSkillReport);
			// export functionality
			GeneralHelpers::exportToExcel($exportColumnNames, $cambridgeLinguaSkillReport->all(), FILENAME_CONSTANT_CAMBRIDGE_LINGUA_SKILL_REPORT);
		}
		$cambridgeLinguaSkillData = compact('instituteTypeOptions', 'stateOptions', 'cityOptions', 'candidateRangeOptions', 'CambridgeLinguaSkillSearchResult');

		return View::make('publisher::linguaskill.index', $cambridgeLinguaSkillData);
	}
}
