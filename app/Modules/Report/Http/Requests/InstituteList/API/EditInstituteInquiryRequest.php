<?php

namespace App\Modules\Report\Http\Requests\InstituteList\API;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EditInstituteInquiryRequest
 * @package App\Modules\Report\Http\Requests
 */
class EditInstituteInquiryRequest extends FormRequest {

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
			'member_id'      => 'required',
			'category_id' => 'required',
			'city'        => 'required',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'member_id'      => trans('report::institute-list.index.select_ref_by'),
			'category_id' => trans('report::institute-list.index.category'),
			'city'        => trans('report::institute-list.index.city'),
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
			'status'  => 0,
			'message' => $message
		];

		return parent::response($responseErrors);
	}


}
