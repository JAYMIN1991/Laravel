<?php

namespace App\Modules\Course\Http\Requests\CoursePromotion;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 * @package App\Modules\Course\Http\Requests\CoursePromotion
 */
class CreateRequest extends FormRequest {

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
			'price_type'           => 'sometimes|numeric',
			'public_type'          => 'sometimes|numeric'
		];
	}

	/**
	 * Get custom attributes for validator errors
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'decoded_institute_id' => trans('course::promotion.common.institute_id'),
			'decoded_course_id'    => trans('course::promotion.common.course_id')
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
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
