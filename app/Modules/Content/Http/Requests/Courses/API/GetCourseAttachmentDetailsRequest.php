<?php

namespace App\Modules\Content\Http\Requests\Courses\API;

use App\Common\APIRequestHandlerTrait;
use App\Common\GeneralHelpers;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class getCourseAttachmentDetails
 * @package App\Modules\Content\Http\Requests\Courses\API
 */
class GetCourseAttachmentDetailsRequest extends FormRequest {

	use APIRequestHandlerTrait;

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
			'user_id'       => 'required|numeric',
			'section_id'    => 'required|numeric',
			'content_id'    => 'required|numeric',
			'attachment_id' => 'required|numeric'
		];
	}

	/**
	 * Prepare validation data
	 */
	protected function prepareForValidation() {
		$this->merge([
			'course' => GeneralHelpers::decode($this->route('id')),
		    'user_id' => GeneralHelpers::decode($this->input('user_id')),
			'section_id' => GeneralHelpers::decode($this->route('section_id')),
			'content_id' => GeneralHelpers::decode($this->route('content_id')),
			'attachment_id' => GeneralHelpers::decode($this->route('attachment_id')),
		]);
	}

}
