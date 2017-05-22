<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Modules\Shared\Misc\SalesVisitViewHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StoreRequest
 * @package App\Modules\Sales\Http\Requests\InstCallVisit
 */
class StoreRequest extends FormRequest {

	/* @var array list of valid institute types */
	private $validInstituteType = [
		SalesVisitViewHelper::SELECT_OPTION_NEW_INSTITUTE,
		SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE
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
			'visit_date'              => 'required|date_format:"' . trans('shared::config.input_date_format') . '"|before:"tomorrow"',
			'institute_type'          => 'required|in:' . implode(',', $this->validInstituteType),
			'institute_name'          => [
				'required_if:institute_type,' . SalesVisitViewHelper::SELECT_OPTION_NEW_INSTITUTE,
				'alpha_space',
				Rule::unique(TABLE_BACKOFFICE_INST_INQUIRY, 'institute_name')
			],
			'inst_inquiry_id'         => [
				'required_if:institute_type,' . SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE,
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_INST_INQUIRY, 'inst_inquiry_id')->where('acq_status', 0)

			],
			'contact_person'          => 'required|alpha_space',
			'inst_category_id'        => [
				'required',
				Rule::exists(TABLE_BACKOFFICE_INST_CATEGORY, 'category_id')->where('category_active', 1)
			],
			'contact_person_desig'    => 'required|alpha_space',
			'student_strength'        => 'required|numeric|max:' . MAX_INSTITUTE_STUDENT_STRENGTH,
			'contact_person_phone'    => 'required|numeric|digits:10',
			'address'                 => 'required',
			'city'                    => 'required|alpha_space',
			'state_id'                => [
				'required',
				'numeric',
				Rule::exists(TABLE_STATES, 'state_id')->where('country_id', DEFAULT_COUNTRY_ID)
			],
			'remarks'                 => 'present',
			'contact_person_email_id' => 'email'
		];
	}

	/**
	 * Get custom messages for validator errors
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'visit_date.before' => trans('sales::visit.common.validation.visit_date_before')
		];
	}

	/**
	 * Set field captions against field names from translation
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'visit_date'              => trans('sales::visit.common.visit_date'),
			'institute_type'          => trans('sales::visit.common.institute_type'),
			'inst_inquiry_id'         => trans('sales::visit.common.inst_inquiry_id'),
			'contact_person'          => trans('sales::visit.common.contact_person'),
			'inst_category_id'        => trans('sales::visit.common.category'),
			'contact_person_desig'    => trans('sales::visit.common.designation'),
			'student_strength'        => trans('sales::visit.common.student_strength'),
			'contact_person_phone'    => trans('sales::visit.common.contact_number'),
			'address'                 => trans('sales::visit.common.address'),
			'city'                    => trans('sales::visit.common.city'),
			'remarks'                 => trans('sales::visit.common.remarks'),
			'institute_name'          => trans('sales::visit.common.institute_name'),
			'contact_person_email_id' => trans('sales::visit.common.email'),
		];
	}

//  Keep it for future check
//	/**
//	 * Get the validator instance for the request
//	 * This is overridden to have optional new name or old institute id based validation
//	 *
//	 * @return Validator
//	 */
//	protected function getValidatorInstance() {
//
//		/* @var $validator Validator */
//		$validator = parent::getValidatorInstance();

//      Keep it for future check
//		/* validate if passed institute is already acquired  */
//		if ( $this->get('institute_type') == 2 ) {
//			$validator->after(function () use ( $validator ) {
//
//				/*
//				  When different inst_inquiry_id is passed on post back,
//				  the new institute acquisition status is being checked
//				 */
//				if ( App::make(InstInquiryRepo::class)->isInstituteAcquired($this->input('inst_inquiry_id')) ) {
//
//					$validator->errors()->add('inst_inquiry_id', trans('sales::visit.common.validation.inst_acquired'));
//				}
//
//			});
//		}
//
//		return $validator;
//	}
}
