<?php

namespace App\Modules\Report\Http\Requests;

use App\Common\URLHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NewUsersRequest
 * @package App\Modules\Report\Http\Requests
 */
class NewUsersRequest extends FormRequest {

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
			'date_from' => 'sometimes|date_format:' . trans('shared::config.date_format'),
			'date_to'   => 'sometimes|date_format:' . trans('shared::config.date_format'),
		];
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData() {
		$input = [];

		if ( $this->has('date_from') ) {
			$input['date_from'] = URLHelpers::decodeGetParam($this->get('date_from'));
		}

		if ( $this->has('date_to') ) {
			$input['date_to'] = URLHelpers::decodeGetParam($this->get('date_to'));
		}

		return array_merge(parent::validationData(), $input);
	}


}
