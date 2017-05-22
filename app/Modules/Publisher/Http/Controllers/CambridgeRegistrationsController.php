<?php

namespace App\Modules\Publisher\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Publisher\Repositories\Contracts\CambridgeRegistrationRepo;
use DBLog;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use Session;
use View;

/**
 * Class CambridgeRegistrationsController
 * @package App\Modules\Publisher\Http\Controllers
 */
class CambridgeRegistrationsController extends Controller {

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function index( Request $request ) {
		// Cambridge Registration Repo
		$cambridgeRegistrationRepo = App::make(CambridgeRegistrationRepo::class);

		// Check if search button is submitted
		if ( $request->has('btnsearch') && $request->has('btnsearch') == 'submit' ) {
			// Get search result collection
			$cambridgeRegistrationResult = $cambridgeRegistrationRepo->getCambridgeRegistrationSearch(true)
			                                                         ->appends($request->except('page'));

			// Save search log log to DB
			DBLog::save(LOG_MODULE_CAMBRIDGE_REGISTRATION, $request->input('btnsearch'), 'Search', $request->getRequestUri(), Session::get('user_id'), $cambridgeRegistrationResult);
		}

		//Start exporting
		if ( $request->has('btnexport') && $request->has('btnexport') == 'export' ) {
			try {
				$cambridgeRegistrationReport = $cambridgeRegistrationRepo->getCambridgeRegistrationSearch(false);

				// Export column name
				$exportColumnNames = [
					'reg_name'        => 'Name',
					'reg_email'       => 'Email Id',
					'reg_mobile'      => 'Mobile Number/User ID',
					'reg_institute'   => 'Name of Institution',
					'reg_designation' => 'Designation/Role',
					'reg_experience'  => 'No. of years of English teaching experience',
					'reg_date_text'   => 'Registration Date'
				];

				// Insert DB log for export tracking
				DBLog::save(LOG_MODULE_CAMBRIDGE_REGISTRATION, $request->input('btnexport'), 'Export', $request->getRequestUri(), Session::get('user_id'), $cambridgeRegistrationReport);

				// Check is data exist to export
				if ( ! $cambridgeRegistrationReport->isEmpty() ) {
					// export functionality
					GeneralHelpers::exportToExcel($exportColumnNames, $cambridgeRegistrationReport->all(), FILENAME_CONSTANT_CAMBRIDGE_REGISTRATION_REPORT);
				} else {
					return Redirect::back()->with('message', trans('shared::message.error.nothing_to_export'));
				}

			} catch ( Exception $e ) {
				GeneralHelpers::logException($e);

				return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
			}
		}

		return View::make('publisher::registrations.index', compact('cambridgeRegistrationResult'));
	}
}
