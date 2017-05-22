<?php

namespace App\Modules\Users\Http\Requests\UserSearch\API;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AddRemarksRequest
 * @package App\Modules\Users\Http\Requests\UserSearch
 */
class AddRemarksRequest extends FormRequest {

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
			'remark' => 'required|min:2'
		];

	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'remark' => trans('users::user-search.index.remarks')
		];
	}

	/**
	 * Get the proper failed validation response for the request.
	 *
	 * @param  array $errors
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function response( array $errors ) {
		$message = '';
		foreach ( $errors as $key => $value ) {
			foreach ( $value as $v ) {
				$message .= $v . '<br>';
			}
		}
		$responseErrors = [
			'status'    => 0,
			'message' => $message
		];

		return parent::response($responseErrors);
	}


}
