<?php

namespace App\Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstituteBankRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * @return array
	 */
	public function attributes() {
		return [
			'institute_id'        => trans('account::institute.index.institute'),
			'bank_name'           => trans('account::institute.common.bank_name'),
			'branch_name'         => trans('account::institute.index.branch_name'),
			'branch_address'      => trans('account::institute.index.branch_address'),
			'account_no'          => trans('account::institute.index.account_no'),
			'account_holder_name' => trans('account::institute.index.account_holder_name'),
			'IFSC_code'           => trans('account::institute.index.IFSC_code'),
			'invoice_details'   => trans('account::institute.index.invoice_details'),
			'display_name'      => trans('account::institute.index.display_name'),
			'address'           => trans('account::institute.index.address'),
			'pin_code'          => trans('account::institute.index.pin_code'),
			'footer_remark'     => trans('account::institute.index.footer_remark'),
			'pancard_no'        => trans('account::institute.index.pancard_no'),
			'servicetax_status' => trans('account::institute.index.servicetax_status'),
			'initials_prefix'   => trans('account::institute.index.initials_prefix')
		];
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [//
		];
	}
}
