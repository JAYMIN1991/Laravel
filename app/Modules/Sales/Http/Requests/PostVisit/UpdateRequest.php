<?php

namespace App\Modules\Sales\Http\Requests\PostVisit;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 * @package App\Modules\Sales\Http\Requests\PostVisit
 */
class UpdateRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'visit_date'              => 'required|date_format:"' . trans('shared::config.input_date_format') . '"|before:"tomorrow"',
			'inst_user_id'            => 'required|numeric|institute',
			'contact_person'          => 'required|alpha_space',
			'contact_person_phone'    => 'required|numeric|digits:10',
			'contact_person_desig'    => 'required|alpha_space',
			'remarks'                 => 'present',
			'contact_person_email_id' => 'email'
		];
	}

	/**
	 * Custom label for attributes
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'inst_user_id'            => trans('sales::post-visit.common.institute'),
			'contact_person_phone'    => trans('sales::post-visit.common.contact_number'),
			'contact_person_desig'    => trans('sales::post-visit.common.designation'),
			'visit_date'              => trans('sales::post-visit.common.visit_date'),
			'remarks'                 => trans('sales::post-visit.common.remarks'),
			'contact_person'          => trans('sales::post-visit.common.contact_person'),
			'contact_person_email_id' => trans('sales::post-visit.common.email')
		];

	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		if ( $this->has('inst_user_id') ) {
			$this->merge(['inst_user_id' => GeneralHelpers::decode($this->input('inst_user_id'))]);
		}
	}
}


