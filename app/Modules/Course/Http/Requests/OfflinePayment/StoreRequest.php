<?php

namespace App\Modules\Course\Http\Requests\OfflinePayment;

use App\Common\GeneralHelpers;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest {

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
			'decode_institute_id' => 'required|numeric',
			'decode_course_id'    => 'required|numeric',
			'total_quantity'      => 'required|numeric|min:1|max:100',
			'member_list'         => 'required|numeric|exists:' . TABLE_BACKOFFICE_SALES_TEAM . ',member_id',
			'cheque_amount'       => 'required|numeric',
			'cheque_no'           => 'required|numeric',
			'cheque_date'         => 'required|date',
			'bank_name'           => 'required',
			'branch_name'         => 'required',
			'billing_name'        => 'required',
			'billing_city'        => 'required',
			'billing_pincode'     => 'required|numeric',
			'billing_phone'       => 'required|numeric',
			'billing_address'     => 'required',
			'billing_state'       => 'required',
			'billing_email'       => 'required|email'
		];
	}

	/**
	 * @return array
	 */
	public function attributes() {
		return [
			'decode_institute_id' => trans('course::offline.common.institute_id'),
			'decode_course_id'    => trans('course::offline.common.course_id')
		];
	}

	/**
	 *
	 */
	protected function prepareForValidation() {
		if ( $this->has('institute_id') ) {
			$this->merge(['decode_institute_id' => GeneralHelpers::decode($this->get('institute_id'))]);
		}
		if ( $this->has('course_id') ) {
			$this->merge(['decode_course_id' => GeneralHelpers::decode($this->get('course_id'))]);
		}
	}

	/**
	 * Get validate selected course with different criteria
	 */
	protected function getValidatorInstance() {
		$validator = parent::getValidatorInstance();

		$validator->after(function () use ( $validator ) {
			$courseOfflinePaymentRepo = \App::make(CourseOfflinePaymentRepo::class);
			$courseId = GeneralHelpers::decode($this->get('course_id'));
			$totalBuyer = GeneralHelpers::clearParam($this->get('total_quantity'), PARAM_RAW_TRIMMED);

			$validateSubscribe = $courseOfflinePaymentRepo->getCourseCanUserSubscribe($courseId, $totalBuyer);
			if ( $validateSubscribe != COURSE_SUB_VALID_SUCCESS ) {

				switch ( $validateSubscribe ) {

					case COURSE_SUB_VALID_NO_COURSE:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_not_exist'));

						return $validator;

					case COURSE_SUB_VALID_NOT_PUBLISHED:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_not_publisher'));

						return $validator;

					case COURSE_SUB_VALID_DISABLED:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_not_enabled'));

						return $validator;

					case COURSE_SUB_VALID_PLAN_EXPIRED:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_is_expired'));

						return $validator;

					case COURSE_SUB_VALID_IS_FREE:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_is_not_paid'));

						return $validator;

					case COURSE_SUB_VALID_MAX_LIMIT:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.course_subscription_greater_zero'));

						return $validator;

					case COURSE_SUB_VALID_REMAINING_SUBSCRIPTION:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.no_subscription_remaining'));

						return $validator;

					case COURSE_SUB_VALID_INSUFFICIENT_SUBSCRIPTION:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.required_subscription_no_remaining'));

						return $validator;

					case COURSE_SUB_VALID_DATE_EXPIRED:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.enrollment_end_date'));

						return $validator;

					case COURSE_SUB_VALID_END_DATE:
						$validator->errors()
						          ->add('course', trans('course::offline.common.validation.end_date_finished'));

						return $validator;
				}
			}
		});

		return $validator;
	}
}
