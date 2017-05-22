<?php

namespace App\Modules\Account\Http\Requests\commission;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexRequest
 * @package App\Modules\Account\Http\Requests\commission
 */
class IndexRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'dec_institute_id' => 'sometimes|institute'
		];
	}

	/**
	 * @return array
	 */
	public function attributes() {
		return [
			'dec_institute_id' => trans('account::user-commission.common.institute_name')
		];
	}

	/**
	 * @return array
	 */
	protected function validationData() {
		$input = [];

		if ( $this->has('institute_id') ) {
			$input['dec_institute_id'] = GeneralHelpers::decode($this->input('institute_id'));
		}

		$this->merge($input);

		return parent::validationData();
	}
}
