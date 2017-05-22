<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Shared\Misc\SalesVisitViewHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Validator;

/**
 * Class UpdateRequest
 *
 * @package App\Modules\Sales\Http\Requests\InstCallVisit
 */
class UpdateRequest extends FormRequest {

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
			'sales_visit_id'          => [
				'required',
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_SALES_VISIT, 'sales_visit_id')->where('is_deleted', 0)
			],
			'visit_date'              => 'required|date_format:"' . trans("shared::config.date_format") . '"|before:"tomorrow"',
			'institute_type'          => 'required|in:' . SalesVisitViewHelper::SELECT_OPTION_EXISTING_INSTITUTE,
			'inst_inquiry_id'         => 'required|numeric|not_in:0|exists:' . TABLE_BACKOFFICE_INST_INQUIRY . ',inst_inquiry_id',
			'contact_person'          => 'required|alpha_space',
			'inst_category_id'        => [
				'required',
				Rule::exists(TABLE_BACKOFFICE_INST_CATEGORY, 'category_id')->where('category_active', 1)
			],
			'contact_person_desig'    => 'required|alpha_space',
			'student_strength'        => 'required|numeric|max:99999',
			'contact_person_phone'    => 'required|numeric|digits:10',
			'address'                 => 'required|no_tags',
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
	 * Get custom attributes for validator errors
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'visit_date'              => trans('sales::visit.common.visit_date'),
			'institute_type'          => trans('sales::visit.common.institute_type'),
			'inst_inquiry_id'         => trans('sales::visit.common.inst_inquiry_id'),
			'contact_person'          => trans('sales::visit.common.contact_person'),
			'inst_category_id'        => trans('sales::visit.common.inst_category_id'),
			'contact_person_desig'    => trans('sales::visit.common.designation'),
			'student_strength'        => trans('sales::visit.common.student_strength'),
			'contact_person_phone'    => trans('sales::visit.common.add.contact_person_phone'),
			'address'                 => trans('sales::visit.common.address'),
			'city'                    => trans('sales::visit.common.city'),
			'remarks'                 => trans('sales::visit.common.remarks'),
			'institute_name'          => trans('sales::visit.common.institute_name'),
			'contact_person_email_id' => trans('sales::visit.common.email'),
		];
	}

	/**
	 * Get custom messages for validator errors
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'visit_date.before' => trans('sales::visit.common.validation.visit_date_before'),
		];
	}

	/**
	 * Get the validator instance for the request
	 * This is overridden to have optional new name or old institute id based validation
	 *
	 * @return Validator
	 */
	protected function getValidatorInstance() {

		/* @var $validator Validator */
		$validator = parent::getValidatorInstance();

		/* validate if passed institute_id is different and is already acquired  */
		if ( $this->get('institute_type') == 2 ) {
			$validator->after(function () use ( $validator ) {

				$salesVisitId = $this->route('id');
				$instInquiryId = $this->input('inst_inquiry_id');

				/*
				 *  Check conditions
				 *  Institute Inquiry Id should match with Sales Visit Id
				 *  Institute is actually acquired or not
				 */
				if ( ! App::make(SalesVisitRepo::class)
				          ->checkInquiryIdVisitIdCombination($salesVisitId, $instInquiryId) && App::make(InstInquiryRepo::class)
				                                                                                  ->isInstituteAcquired($instInquiryId)
				) {
					$validator->errors()->add('inst_inquiry_id', trans('sales::visit.common.error.inst_already_acq'));
				}
			});
		}

		return $validator;
	}

	/**
	 * Custom Validation data
	 *
	 * @return array
	 */
	protected function validationData() {
		return array_merge($this->request->all(), [
			'sales_visit_id' => $this->route('id'),
		]);
	}
}
