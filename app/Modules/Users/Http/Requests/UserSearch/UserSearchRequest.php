<?php

namespace App\Modules\Users\Http\Requests\UserSearch;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserSearchRequest
 *
 * @package App\Modules\Users\Http\Requests
 */
class UserSearchRequest extends FormRequest {

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
			'user_email'       => 'email',
			'user_mobile'      => 'digits:10',
			'account_verified' => 'in:0,1,2'
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'first_name'       => trans('users::user-search.index.first_name'),
			'last_name'        => trans('users::user-search.index.last_name'),
			'user_name'        => trans('users::user-search.common.user_name'),
			'account_verified' => trans('users::user-search.index.verified'),
			'user_email'       => trans('users::user-search.index.email'),
			'user_mobile'      => trans('users::user-search.index.mobile'),
			'deleted_only'     => trans('users::user-search.index.deleted_only')
		];
	}
}
