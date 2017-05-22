<?php

namespace App\Modules\Publisher\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CambridgeTKTExamStoreRequest extends FormRequest {

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
			'module_type' => 'required|min:1|digits_between: 1,5',
			'city_type'   => 'required|min:1|digits_between: 1,5',
			'date'        => 'date',
			'url'         => 'required|url',
		];
	}
}
