<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Modules\Sales\Repositories\Contracts\InstCategoryRepo;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Shared\Misc\ViewHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Session;

/**
 * Class IndexRequest
 * @package App\Modules\Sales\Http\Requests\InstCallVisit
 */
class IndexRequest extends FormRequest {

	/**
	 * @var array $validVisitBy List of valid member_ids, filled from database
	 */
	private $validVisitBy = [];

	/**
	 * @var array $validInquiryConverted List of valid Inquiry Converted
	 */
	private $validInquiryConverted = [];

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
			'visit_by'          => 'numeric|in:' . implode(',', $this->validVisitBy),
			'institute'         => [
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_INST_INQUIRY, 'inst_inquiry_id')->where('inst_list_acq', 0)
			],
			'category'          => [
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_INST_CATEGORY, 'category_id')->where('category_active', 1)
			],
			'inquiry_converted' => 'numeric|in:' . implode(',', $this->validInquiryConverted),
			'visit_date_from'   => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
			'visit_date_to'     => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"'

		];
	}

	/**
	 * Set field captions against field names from translation
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'visit_by'          => trans('sales::visit.common.visit_by'),
			'institute'         => trans('sales::visit.common.institute_name'),
			'category'          => trans('sales::visit.common.category'),
			'inquiry_converted' => trans('sales::visit.common.inquiry_converted'),
			'visit_date_from'   => trans('sales::visit.common.visit_date_from'),
			'visit_date_to'     => trans('sales::visit.common.visit_date_to')
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		// TODO: check if there is any possible direct db query option instead of fetching list
		$this->validVisitBy = App::make(SalesTeamRepo::class)
		                         ->getListByUserId(Session::get('user_id'))
		                         ->keys()
		                         ->toArray();

		$this->validInquiryConverted = [
			ViewHelper::SELECT_OPTION_VALUE_ANY,
			ViewHelper::SELECT_OPTION_VALUE_NO,
			ViewHelper::SELECT_OPTION_VALUE_YES
		];
	}
}
