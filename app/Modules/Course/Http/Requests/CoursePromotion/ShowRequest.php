<?php

namespace App\Modules\Course\Http\Requests\CoursePromotion;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowRequest
 * @package App\Modules\Course\Http\Requests\CoursePromotion
 */
class ShowRequest extends FormRequest {

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
			'decoded_course_id' => 'sometimes|numeric|exists:' . TABLE_COURSES . ',course_id',
		];
	}

	/**
	 * Get custom attributes for validator errors
	 *
	 * @return array
	 */
	public function attributes() {
		return ['decoded_course_id' => trans('course::promotion.common.course_id')];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {

		if ( $this->route('id') ) {
			$this->merge(['decoded_course_id' => GeneralHelpers::decode($this->route('id'))]);
		}
	}
}
