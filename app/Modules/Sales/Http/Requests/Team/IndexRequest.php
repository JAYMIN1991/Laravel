<?php

namespace App\Modules\Sales\Http\Requests\Team;

use App\Modules\Shared\Misc\ViewHelper;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexRequest
 * @package App\Modules\Sales\Http\Requests\Team
 */
class IndexRequest extends FormRequest {

	/**
	 * Valid options for isLeft checkbox
	 *
	 * @var array
	 */
	private $validIsLeft = [
		ViewHelper::SELECT_OPTION_VALUE_ANY,
		ViewHelper::SELECT_OPTION_VALUE_YES,
		ViewHelper::SELECT_OPTION_VALUE_NO
	];

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
			'first_name'       => 'sometimes|alpha',
			'last_name'        => 'sometimes|alpha',
			'city_name'        => 'sometimes|alpha',
			'is_left'          => 'sometimes|in:' . implode(',', $this->validIsLeft),
			'parent_member_id' => 'sometimes|numeric'
		];
	}
}
