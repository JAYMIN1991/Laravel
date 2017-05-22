<?php

namespace App\Modules\Sales\Http\Requests\PostVisit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Route;

/**
 * Class EditRequest
 * @package App\Modules\Sales\Http\Requests\PostVisit
 */
class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    return true;
    }

	/**
	 * Get the validation rules that apply to the request
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'after_sales_visit_id' => [
				'required',
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_AFTER_SALES_VISIT, 'after_sales_visit_id')->where('is_deleted', 0)
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
			'after_sales_visit_id' => Route::input('id'),
		]);
	}

	/**
	 * Get custom attributes for validator errors
	 *
	 * @return array
	 */
	public function attributes() {
		return ['after_sales_visit_id' => trans('sales::post-visit.common.after_sales_visit_id')];
	}

}
