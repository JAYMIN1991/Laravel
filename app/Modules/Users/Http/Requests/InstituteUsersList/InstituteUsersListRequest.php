<?php

namespace App\Modules\Users\Http\Requests\InstituteUsersList;

use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InstituteUsersListRequest
 * @package App\Modules\Users\Http\Requests\InstituteUsersList
 */
class InstituteUsersListRequest extends FormRequest
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
    	if ($this->has('inst_users_button')){
		    return [
			    'inst_id' => 'required|numeric',
			    'user_role_id' => 'sometimes|in:1,2,3',
			    'user_plan_status' => 'sometimes|in:0,1,2',
			    'inst_users_button' => 'sometimes|in:1,2,3'
		    ];
	    }

	    return [];
    }

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'inst_id' => trans('users::institute-users-list.index.institute'),
		    'user_role_id' => trans('users::institute-users-list.index.user_type'),
			'user_plan_status' => trans('users::institute-users-list.index.plan_status'),
		];
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'inst_users_button.in' => trans('shared::message.error.invalid_request_type'),
		];
	}


	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData() {
		$input = [];

		// If request has institute Id than decode it and merge to request
		if ( $this->has('inst_id') ) {
			$input['inst_id'] = GeneralHelpers::decode($this->input('inst_id'));
		}

		$this->merge($input);

		return parent::validationData(); // TODO: Change the autogenerated stub
	}
}