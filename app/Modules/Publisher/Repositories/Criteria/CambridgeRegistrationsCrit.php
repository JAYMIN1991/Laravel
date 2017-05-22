<?php

namespace App\Modules\Publisher\Repositories\Criteria;

use App\Common\GeneralHelpers;
use DB;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class CambridgeRegistrationsCrit
 * @package namespace App\Modules\Publisher\Repositories\Criteria;
 */
class CambridgeRegistrationsCrit extends AbstractCriteria {

	protected $request;

	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$registrationsName = GeneralHelpers::clearParam($this->request->input('registrations_name'), PARAM_RAW_TRIMMED);
		$registrationsMobileOrEmail = GeneralHelpers::clearParam($this->request->input('mobile_no_email_id'), PARAM_RAW_TRIMMED);
		$registrationsDateFrom = GeneralHelpers::clearParam($this->request->input('registration_date_from'), PARAM_RAW_TRIMMED);
		$registrationsDateTo = GeneralHelpers::clearParam($this->request->input('registration_date_to'), PARAM_RAW_TRIMMED);
		$registrationsEmail = GeneralHelpers::clearParam($this->request->input('registration_email_id'), PARAM_RAW_TRIMMED);
		$registrationsInstituteName = GeneralHelpers::clearParam($this->request->input('institute_name'), PARAM_RAW_TRIMMED);

		if ( $this->request->has('registrations_name') ) {
			$model->where('reg_name', 'like', '%' . $registrationsName . '%');
		}

		if ( $this->request->has('mobile_no_email_id') ) {
			$model->where('reg_mobile', 'like', '%' . $registrationsMobileOrEmail . '%');
		}

		if ( $this->request->has('registration_email_id') ) {
			$model->where('reg_email', 'like', '%' . $registrationsEmail . '%');
		}

		if ( $this->request->has('institute_name') ) {
			$model->where('reg_institute', 'like', '%' . $registrationsInstituteName . '%');
		}

		if ( $this->request->has('registration_date_from') && empty($this->request->has('registration_date_to')) ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(reg_date))'), '>=', GeneralHelpers::saveFormattedDate($registrationsDateFrom));
		}

		if ( $this->request->has('registration_date_to') && empty($this->request->has('registration_date_from')) ) {
			$model->where(DB::raw('DATE(FROM_UNIXTIME(reg_date))'), '<=', GeneralHelpers::saveFormattedDate($registrationsDateTo));
		}

		if ( $this->request->has('registration_date_to') && $this->request->has('registration_date_from') ) {
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(reg_date))'), [
				GeneralHelpers::saveFormattedDate($registrationsDateFrom),
				GeneralHelpers::saveFormattedDate($registrationsDateTo)
			]);
		}

		return $model;
	}
}
