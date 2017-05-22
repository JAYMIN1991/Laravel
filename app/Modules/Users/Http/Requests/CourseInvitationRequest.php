<?php

namespace App\Modules\Users\Http\Requests;

use App;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use Excel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Request;

/**
 * Class CourseInvitationRequest
 * @package App\Modules\Users\Http\Requests
 */
class CourseInvitationRequest extends FormRequest {

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
		if ( Request::isMethod("POST") ) {
			return [
				'decrypted_course_id' => [
					'required',
					Rule::exists(TABLE_COURSES, 'course_id')
				],
				'invite_by'           => 'required',
				'invite_manual_text'  => 'required_if:invite_by,manual',
				'invite_file'         => 'required_if:invite_by,file|file'
			];
		}

		return [];
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData() {

		if ( $this->has('course_id') ) {
			$this->merge(['decrypted_course_id' => App\Common\GeneralHelpers::decode($this->get('course_id'))]);
		}

		return parent::validationData();
	}

	/**
	 * Get the validator instance for the request.
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function getValidatorInstance() {
		$validator = parent::getValidatorInstance();
		$validator->sometimes('invite_manual_text', 'required_without:invite_file', function ( $input ) {
			if ( $input->invite_by == 'manual' && $this->has('invite_manual_text') ) {
				$emails['emails'] = array_map(function ( $email ) {
					return ['email' => trim($email)];
				}, explode(",", $this->get('invite_manual_text')));

				$this->merge($emails);

				return true;
			}

			return false;
		});
		$validator->sometimes('invite_file', 'required_without:invite_manual_text|file', function ( $input ) {
			if ( $input->invite_by == 'file' && $this->hasFile('invite_file') ) {
				$emails['emails'] = Excel::load($this->file('invite_file'))->toArray();
				$this->merge($emails);

				return true;
			}

			return false;
		});

		return $validator;
	}

	/**
	 * Get the proper failed validation response for the request.
	 *
	 * @param  array $errors
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function response( array $errors ) {

		if ( ! $this->has('course_id') ) {
			return parent::response($errors);
		}

		$courseRepo = App::make(CourseRepo::class);
		$course = $courseRepo->getCoursesById($this->get('decrypted_course_id'));

		if ( $this->expectsJson() ) {
			return new JsonResponse($errors, 422);
		}

		return $this->redirector->to($this->getRedirectUrl())->withInput(array_merge($this->except($this->dontFlash),
			['course_name' => $course['course_name']]))->withErrors($errors, $this->errorBag);

	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'decrypted_course_id' => trans('users::course-invitation.index.course_id'),
			'invite_by'           => trans('users::course-invitation.index.invite_by'),
			'invite_manual_text'  => trans('users::course-invitation.index.invite_manual_text'),
			'invite_file'         => trans('users::course-invitation.index.invite_file')
		];
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'invite_file.required_if'        => trans('users::course-invitation.validation.invite_file.required_if'),
			'invite_manual_text.required_if' => trans('users::course-invitation.validation.invite_manual_text.required_if'),
		];
	}


}
