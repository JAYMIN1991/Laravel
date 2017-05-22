<?php

namespace App\Modules\Content\Http\Requests\Courses;

use App\Common\GeneralHelpers;
use App\Modules\Shared\Misc\CoursesReviewViewHelper;
use App\Modules\Shared\Misc\ViewHelper;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReviewRequest
 * @package App\Modules\Content\Http\Requests\Courses
 */
class ReviewRequest extends FormRequest {

	private $validCourseReviewStatus = [
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
			'decrypted_institute'  => 'sometimes|numeric|institute',
			'course_name'          => 'sometimes|alpha_space',
			'course_type'          => 'sometimes|numeric|in:' . ViewHelper::SELECT_COURSE_TYPE_TIME_BOUND . ',' . ViewHelper::SELECT_COURSE_TYPE_SELF_PACED,
			'course_review_status' => 'sometimes|numeric|in:' . implode(',', $this->validCourseReviewStatus),
			'date_from'            => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
			'date_to'              => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		if ( $this->has('institute') ) {
			$this->merge(['decrypted_institute' => GeneralHelpers::decode($this->input('institute'))]);
		}
	}

	/**
	 * Get custom attributes for validator errors
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'decrypted_institute'  => trans('content::course.review.institute'),
			'course_name'          => trans('content::course.review.course_name'),
			'course_type'          => trans('content::course.review.course_type'),
			'course_review_status' => trans('content::course.review.course_review_status'),
			'date_from'            => trans('content::course.review.date_from'),
			'date_to'              => trans('content::course.review.date_to'),
		];
	}


}
