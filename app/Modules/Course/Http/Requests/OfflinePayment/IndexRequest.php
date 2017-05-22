<?php

namespace App\Modules\Course\Http\Requests\OfflinePayment;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

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
			'decoded_institute_id' => 'sometimes|numeric|institute',
			'decoded_course_id'    => 'sometimes|numeric|exists:' . TABLE_COURSES . ',course_id',
		];
	}

	/**
	 * Merge new input fields depend on existing fields for institute and course id validation
	 */
	protected function prepareForValidation() {
		if ( $this->has('institute_id') ) {
			$this->merge(['decoded_institute_id' => GeneralHelpers::decode($this->input('institute_id'))]);
		}
		if ( $this->has('course_id') ) {
			$this->merge(['decoded_course_id' => GeneralHelpers::decode($this->input('course_id'))]);
		}
	}
}
