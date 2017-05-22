<?php

namespace App\Modules\Report\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserCountRequest
 * @package App\Modules\Report\Http\Requests
 */
class UserCountRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'date_from'            => 'sometimes|date_format:' . trans('shared::config.date_format'),
	        'date_to'              => 'sometimes|date_format:' . trans('shared::config.date_format')
        ];
    }

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'date_from'            => trans('report::users-count.index.date_from'),
			'date_to'              => trans('report::users-count.index.date_to')
		];
	}
}
