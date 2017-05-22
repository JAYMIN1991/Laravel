<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Route;

/**
 * Class EditRequest
 * @package App\Modules\Sales\Http\Requests\InstCallVisit
 */
class EditRequest extends FormRequest {

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
			'sales_visit_id' => [
				'required',
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_SALES_VISIT, 'sales_visit_id')->where('is_deleted', 0)
			]
		];
	}

	/**
	 * Custom Validation data
	 *
	 * @return array
	 */
	protected function validationData() {
		return array_merge($this->request->all(), [
			'sales_visit_id' => Route::input('id'),
		]);
	}
}
