<?php

namespace App\Modules\Account\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Account\Http\Requests\InstituteBankRequest;
use App\Modules\Account\Repositories\InstituteBank;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Illuminate\Routing\Controller;
use Session;
use View;

/**
 * @property  InstituteBank
 */
class InstituteBankController extends Controller
{
    protected $instituteName;

    protected $instituteBank;

    /**
     * @param InstituteBankRequest $request
     * @param InstituteBank $instituteBank
     * @return \Illuminate\Contracts\View\View
     * @internal param InstituteBank $instituteBank
     */
    public function index(InstituteBankRequest $request, InstituteBank $instituteBank) {
        if ($request->has('institute_id')) {
            $instituteName = App::make(UserMasterRepo::class)->getInstituteByOwnerId(GeneralHelpers::decode($request->get('institute_id')))['user_school_name'];
            $this->instituteBank = $instituteBank;

            // Search bank and invoice details using institute id
            $bankDetails = $this->instituteBank->getBankAndInvoiceByInstituteId(GeneralHelpers::decode($request->get('institute_id')));

	        // Insert DB log for tracking
	        if(!empty($bankDetails)){
		        DBLog::save(LOG_MODULE_INSTITUTE_BANK_DETAILS, GeneralHelpers::decode($request->get('institute_id')), 'Search', $request->getRequestUri(), Session::get('user_id'), $bankDetails);
	        }
        }
        return View::make('account::institute-bank', compact('instituteName', 'bankDetails'));
    }
}
