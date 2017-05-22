<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Modules\Sales\Repositories\Contracts\AfterSalesVisitRepo;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class DestroyRequest
 * @package App\Modules\Sales\Http\Requests\InstCallVisit
 */
class DestroyRequest extends FormRequest {

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
			'sales_visit_id' => [
				'required',
				'numeric',
				Rule::exists(TABLE_BACKOFFICE_SALES_VISIT, 'sales_visit_id')->where('is_deleted', 0),
			],
		];

	}

	/**
	 * Get the validator instance for the request
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function getValidatorInstance() {

		/* @var $validator \Illuminate\Validation\Validator */
		$validator = parent::getValidatorInstance();

		$validator->after(function () use ( $validator ) {
			if ( $this->validateAfterSalesVisitEntry() ) {
				$validator->errors()
				          ->add('after_sales_visit', trans('sales::visit.destroy.validation.after_visit_exists'));
			}
		});

		return $validator;
	}

	/**
	 * Check for after sales visit entry
	 *
	 * @return bool
	 */
	public function validateAfterSalesVisitEntry() {
		$salesVisitId = $this->route('id');
		$instInquiryId = App::make(SalesVisitRepo::class)->getDetail($salesVisitId)['inst_inquiry_id'];
		$afterSalesVisitEntry = App::make(AfterSalesVisitRepo::class)
		                           ->getAfterSalesVisitDetailByInstituteId($instInquiryId, ['after_sales_visit_id']);

		/* Check if after sales visit entry is exists */
		return empty($afterSalesVisitEntry) ? false : true;
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
