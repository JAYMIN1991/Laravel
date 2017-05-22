<?php

namespace App\Modules\Content\Http\Requests\Courses;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowRequest
 * @package App\Modules\Content\Http\Requests\Courses
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
			'course' => 'required|numeric|exists:' . TABLE_COURSES . ',course_id'
		];
	}

	/**
	 * Prepare validation data
	 */
	protected function prepareForValidation() {
		$this->merge(['course' => GeneralHelpers::decode($this->route('id'))]);
	}
}
