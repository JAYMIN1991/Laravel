<?php

namespace App\Modules\Users\Http\Requests;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Class CopyLearnersRequest
 * @package App\Modules\Users\Http\Requests
 */
class CopyLearnersRequest extends FormRequest {

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
		if ( $this->isMethod('POST') ) {
			return [
				'from_institute' => 'required|institute',
				'from_courses'   => 'required',
				'to_institute'   => 'required|institute',
				'to_course'      => 'required',
			];
		}

		return [];
	}

	/**
	 * Configure the validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator $validator
	 *
	 * @return void
	 */
	public function withValidator( $validator ) {

		if ( $this->isMethod('POST') ) {
			if ( $this->has('from_courses') ) {
				$validator->after(function ( $validator ) {
					/** @var Validator $validator */
					$errors = $this->validateCourses($this->get('from_courses'),
						GeneralHelpers::clearParam($this->get('from_institute'), PARAM_RAW_TRIMMED), 'from_courses');

					if ( isset($errors['field']) && isset($errors['message']) ) {
						$validator->errors()->add($errors['field'], $errors['message']);
					}
				});
			}

			if ( $this->has('to_course') ) {
				$validator->after(function ( $validator ) {
					/** @var Validator $validator */
					$errors = $this->validateCourses($this->get('to_course'),
						GeneralHelpers::clearParam($this->get('to_institute'), PARAM_RAW_TRIMMED), 'to_course');

					if ( isset($errors['field']) && isset($errors['message']) ) {
						$validator->errors()->add($errors['field'], $errors['message']);
					}
				});
			}
		}
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData() {
		// If ids of course and institute are encoded, decode it here
		$input = [];

		if ( $this->has('from_institute') ) {
			$input['from_institute'] = GeneralHelpers::decode($this->input('from_institute'));
		}

		if ( $this->has('to_institute') ) {
			$input['to_institute'] = GeneralHelpers::decode($this->input('to_institute'));
		}

		if ($this->has('from_courses')) {
			foreach ($this->input('from_courses') as $index => $courseId) {
				$input['from_courses'][$index] = GeneralHelpers::decode($courseId);
			}
		}

		if ($this->has('to_course')) {
			$input['to_course'] = GeneralHelpers::decode($this->input('to_course'));
		}

		$this->merge($input);

		return parent::validationData();
	}

	/**
	 * Validate the course
	 *
	 * @param int    $courses     Id of the course
	 * @param int    $instituteId Id of the institute
	 * @param string $field       Field which you are validating
	 *
	 * @return array
	 */
	private function validateCourses( $courses, $instituteId, $field = 'from_courses' ) {
		$error = [];
		$courseRepo = App::make(CourseRepo::class);
		$courses = $courseRepo->getCoursesById($courses, [
			'course_owner',
			'course_enabled',
			'course_status',
			'course_plan_expired',
			'course_is_free'
		]);

		if ( is_array($courses) ) {
			$courses = collect([0 => $courses]);
		}

		for ( $index = 0 ; $index < $courses->count() ; $index++ ) {
			$course = $courses->offsetGet($index);

			if ( $course['course_owner'] != $instituteId ) {
				$error['field'] = $field;
				$error['message'] = trans('users::copy-learners.error.' . $field . '.course_institute_not_match', [
					'course_name' => $course['course_name']
				]);

				return $error;
			} elseif ( $course['course_is_free'] != 1 || $course['course_enabled'] != 1 || $course['course_plan_expired'] != 0 || $course['course_status'] != COURSE_STATUS_PUBLISH ) {
				$error['field'] = $field;
				$error['message'] = trans('users::copy-learners.error.' . $field . '.course_institute_not_match', [
					'course_name' => $course['course_name']
				]);

				return $error;
			}
		}

		$this->merge([$field => $courses]);

		return $error;
	}
}
