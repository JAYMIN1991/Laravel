<?php

namespace App\Modules\Sales\Http\Requests\Acquisition;

use App\Common\GeneralHelpers;
use App\Modules\Shared\Misc\AcquisitionReportViewHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class Report
 * @package App\Modules\Sales\Http\Requests\Acquisition
 */
class ReportRequest extends FormRequest {

	/**
	 * Valid options for TotalPost field
	 *
	 * @var array
	 */
	private $validTotalPost = [
		AcquisitionReportViewHelper::SELECT_OPTION_GREATER_THAN,
		AcquisitionReportViewHelper::SELECT_OPTION_LESS_THAN,
		AcquisitionReportViewHelper::SELECT_OPTION_EQUALS_TO
	];

	/**
	 * Valid options for DateRangeOn field
	 *
	 * @var array
	 */
	private $validDateRangeOn = [
		AcquisitionReportViewHelper::SELECT_OPTION_DATE_RANGE_ON_INSTITUTE,
		AcquisitionReportViewHelper::SELECT_OPTION_DATE_RANGE_ON_USER
	];

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
		$rules = [
			'ref_by.*'       =>'required|numeric|exists:'. TABLE_BACKOFFICE_SALES_TEAM .',member_id',
			'date_from'      => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
			'date_to'        => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
			'course_user_id' => 'sometimes|numeric|institute',
			'post_type'      => 'sometimes|numeric|in:' . implode(',', $this->validTotalPost),
			'post_value'     => 'sometimes|numeric|min:1',
			'date_range_on'  => 'sometimes|numeric|in:' . implode(',', $this->validDateRangeOn)
		];

		return $rules;
	}

	/**
	 * Set field captions against field names from translation
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'ref_by'         => trans('sales::acquisition.ref_by'),
			'date_from'      => trans('sales::acquisition.date_from'),
			'date_to'        => trans('sales::acquisition.date_to'),
			'course_user_id' => trans('sales::acquisition.institute'),
			'post_type'      => trans('sales::acquisition.post_type'),
			'post_value'     => trans('sales::acquisition.post_value'),
			'date_range_on'  => trans('sales::acquisition.date_range_on')
		];
	}

	/**
	 * Get custom messages for validator errors
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'ref_by.*.required' => trans('sales::acquisition.validation.ref_by_required'),
			'ref_by.*.numeric'  => trans('sales::acquisition.validation.ref_by_numeric'),
			'ref_by.*.exists'   => trans('sales::acquisition.validation.ref_by_exists'),
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {

		if ( $this->has('course_user_id') ) {
			$this->merge(['course_user_id' => GeneralHelpers::decode($this->input('course_user_id'))]);
		}

	}

}
