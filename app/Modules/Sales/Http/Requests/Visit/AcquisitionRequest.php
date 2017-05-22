<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Route;

/**
 * Class AcquisitionRequest
 * @package App\Modules\Sales\Http\Requests\Visit
 */
class AcquisitionRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
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
				Rule::exists(TABLE_BACKOFFICE_SALES_VISIT, 'sales_visit_id')->where('is_deleted', 0)
			]
		];
	}

	/**
	 * Get the validator instance for the request
	 *
	 * @return Validator
	 */
	protected function getValidatorInstance() {

		/* @var $validator Validator */
		$validator = parent::getValidatorInstance();
		$validator->after(function () use ( $validator ) {

			/* If institute is acquired against different sales visit */
			if ( ! $this->validateInquiryAgainstSalesVisitEntry() ) {
				$validator->errors()->add('inst_acquired', trans('sales::visit.common.error.inst_already_acq'));
			}
		});

		return $validator;
	}

	/**
	 *  Validate if Institute inquiry is acquired against current sales visit
	 *
	 * @return bool false if Institute is acquired but not against sales visit, else returns true
	 */
	public function validateInquiryAgainstSalesVisitEntry() {

		$salesVisitId = $this->route('id');
		$salesVisitDetails = App::make(SalesVisitRepo::class)->getInstituteAndCategoryDetail($salesVisitId);

		if ( $salesVisitDetails['acq_status'] == 1 && $salesVisitDetails['visit_acq_status'] == 0 ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Custom Validation data
	 *
	 * @return array
	 */
	protected function validationData() {
		return array_merge($this->request->all(), [
			'sales_visit_id' => Route::input('id'),
		]);
	}
}
