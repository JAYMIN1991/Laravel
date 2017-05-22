<?php

namespace App\Modules\Users\Http\Requests\InstituteUsersList\API;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ChangeMobileRequest
 * @package App\Modules\Users\Http\Requests\InstituteUsersList
 */
class ChangeMobileRequest extends FormRequest
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
	        'mobile' => 'required|digits:10',
        ];
    }

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'mobile' => trans('users::institute-users-list.index.new_mobile')
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
