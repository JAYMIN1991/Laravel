<?php
namespace App\Modules\Utility\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChangePasswordRequest
 * @package App\Modules\Utility\Http\Requests
 */
class ChangePasswordRequest extends FormRequest {

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
			'new_password'          => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:new_password',
		];
	}

	/**
	 * Override attributes function to get field names
	 * @return array
	 */
	public function attributes() {
		return [
			'new_password'          => trans('utility::change-password.index.new_password'),
			'password_confirmation' => trans('utility::change-password.index.verify_new_password'),
		];
	}
}
