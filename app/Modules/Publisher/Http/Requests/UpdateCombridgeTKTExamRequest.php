<?php

namespace App\Modules\Publisher\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCombridgeTKTExamRequest extends FormRequest {

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
			'module_type'           => 'required|numeric',
			'city_type'             => 'required|numeric',
			'date'                  => 'date',
			'url'                   => 'required|url',
			'cambridge_TKT_exam_id' => 'required|numeric'
		];
	}

	/**
	 *  Set route parameter validation
	 *  pass variable to request and validate it on
	 *  rules function
	 */
	public function prepareForValidation() {
		$this->merge(['cambridge_TKT_exam_id' => $this->route('tkt')]);
	}


	/**
	 * This function will provide language specific
	 * variables text
	 * @return array
	 */
	public function attributes() {
		return [
			'module_type'           => trans('publisher::cambridge-tkt-exam.common.module_type'),
			'city_type'             => trans('publisher::cambridge-tkt-exam.common.city_type'),
			'date'                  => trans('publisher::cambridge-tkt-exam.common.date'),
			'url'                   => trans('publisher::cambridge-tkt-exam.common.url'),
			'cambridge_TKT_exam_id' => trans('publisher::cambridge-tkt-exam.common.cambridge_TKT_exam_id'),
		];
	}
}
