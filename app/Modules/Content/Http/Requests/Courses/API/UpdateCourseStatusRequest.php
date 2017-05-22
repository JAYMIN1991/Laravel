<?php

namespace App\Modules\Content\Http\Requests\Courses\API;

use App\Common\APIRequestHandlerTrait;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Misc\CoursesReviewViewHelper;
use Illuminate\Foundation\Http\FormRequest;
use Purifier;

/**
 * Class UpdateCourseStatusRequest
 * @package App\Modules\Content\Http\Requests\Courses\API
 */
class UpdateCourseStatusRequest extends FormRequest {

	use APIRequestHandlerTrait;

	private $validStatus = [
		CoursesReviewViewHelper::SELECT_OPTION_REVIEW_PENDING,
		CoursesReviewViewHelper::SELECT_OPTION_APPROVED,
		CoursesReviewViewHelper::SELECT_OPTION_NOT_APPROVED,
		CoursesReviewViewHelper::SELECT_OPTION_DEACTIVATED
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
			'status'  => 'required|numeric|in:' . implode(',', $this->validStatus),
			'remarks' => 'required',
			'course'  => 'required|numeric|exists:' . TABLE_COURSES . ',course_id'
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {

		/* Merge route input as course into request object */
		$this->merge(['course' => GeneralHelpers::decode($this->route('id'))]);

		/* Purify the remarks */
		$remarks = Purifier::clean($this->input('remarks'), ['HTML.Allowed'             => 'p,br,strong,em,ul,ol,li,a[href|target]',
		                                                     'Attr.AllowedFrameTargets' => '_blank'
		]);

		/* Merge Purified remarks into request object */
		$this->merge(['remarks' => $remarks]);
	}
}
