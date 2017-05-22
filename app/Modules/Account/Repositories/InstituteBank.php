<?php

namespace App\Modules\Account\Repositories;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Account\Repositories\Contracts\InstituteBankRepo;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class InstCategory
 * @package namespace App\Modules\Account\Repositories;
 * @see     InstituteBank
 */
class InstituteBank extends BaseRepository implements InstituteBankRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'user_bank_id';

	protected $request;

	/**
	 * RequestCriteria constructor
	 *
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		parent::__construct();
		$this->request = $request;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get institute bank and invoice details using institute id
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getBankAndInvoiceByInstituteId( $id ) {
		/* NonDeletedCriteria Handled internally */
		$result = $this->from($this->model() . ' as ub')
		               ->select([
			               'bank_name',
			               'branch_name',
			               'branch_address',
			               'account_no',
			               'account_holder_name',
			               'ifsc_code',
			               'display_name',
			               'address',
			               'pincode',
			               'pancard_no',
			               'servicetax_applicable',
			               'servicetax_no',
			               'initials',
			               'footer_remarks'
		               ])
		               ->join(TABLE_USER_INVOICE_SETTINGS . ' as ii', 'ub.user_id', '=', 'ii.user_id')
		               ->where('ub.user_id', GeneralHelpers::clearParam($id, PARAM_RAW_TRIMMED))
		               ->orderBy('user_bank_id')
		               ->first();

		return $this->parserResult($result);
	}

	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_USER_BANK_DETAILS;
	}
}
