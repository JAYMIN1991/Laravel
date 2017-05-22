<?php

namespace App\Modules\Sales\Http\Requests\Visit;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Route;

/**
 * Class AcquisitionDoRequest
 * @package App\Modules\Sales\Http\Requests\Visit
 */
class AcquisitionDoRequest extends FormRequest {

	/**
	 * @var $salesVisit SalesVisitRepo
	 */
	protected $salesVisit;

	/**
	 * AcquisitionDoRequest constructor
	 *
	 * @param SalesVisitRepo $salesVisitRepo
	 */
	public function __construct( SalesVisitRepo $salesVisitRepo ) {
		parent::__construct();
		$this->salesVisit = $salesVisitRepo;
	}

	/**
	 * Determine if the user is authorized to make this request
	 *
	 * @return bool
	 */
	public function authorize() {
		// validate permission from sales_visit_id
		// get reference from IndexRequest and check member_id in valid member list
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
			    /*
			    keep this code:
			    Rules to invalidate request based on logged in user permission
			    ->where(function($query){
					 $memberCollection = App::make(SalesTeamRepo::class)->getListByUser(Session::get('user_id'))->keys();
					 if(count($memberCollection) == 0) $memberCollection = [-1];
					 $query->whereIn('member_id',$memberCollection);
			    })*/
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

			$salesVisitId = $this->route('id');
			$salesVisitDetail = $this->salesVisit->getInstituteAndCategoryDetail($salesVisitId);

			/* Check if sales Visit detail is exists  */
			if(empty($salesVisitDetail))
			{
				$validator->errors()->add('sales_visit', trans('shared::message.error.not_found'));
				return $validator;
			}

			/* If institute is acquired against different sales visit */
			if ( ! $this->validateInquiryAgainstSalesVisitEntry($salesVisitDetail) ) {
				$validator->errors()->add('inst_acquired', trans('sales::visit.common.error.inst_already_acq'));
			}

			/* If remove acquisition is checked */
			if ( trim($this->input('remove_acq')) == 1 ) {

				/* Institute should not be changed */
				if ( $this->validateInstituteIsChanged($salesVisitDetail) ) {
					$validator->errors()->add('inst_acquired', trans('sales::visit.common.error.inst_invalid'));

				}
			}
		});

		return $validator;
	}

	/**
	 *  Validate if Institute inquiry is acquired against current sales visit
	 *
	 * @param array $salesVisitDetail Details of sales visit entry
	 *
	 * @return bool false if Institute is acquired but not against sales visit, else returns true
	 */
	private function validateInquiryAgainstSalesVisitEntry( $salesVisitDetail) {

		if ( $salesVisitDetail['acq_status'] == 1 && $salesVisitDetail['visit_acq_status'] == 0 ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Validate if Institute is not changed in request
	 *
	 * @param array $salesVisitDetail Details of sales visit entry
	 *
	 * @return bool false if Institute is changed, else returns true
	 */
	private function validateInstituteIsChanged( $salesVisitDetail ) {

		/* @var UserMasterRepo $institute  */
		$institute = App::make(UserMasterRepo::class)->getInstituteByOwnerId($salesVisitDetail['converted_inst_id']);

		if (!empty($institute) && $institute['user_id'] != $this->input('user_id') ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Custom Validation data
	 *
	 * @return array
	 */
	protected function validationData() {
		return array_merge($this->all(), [
			'sales_visit_id' => Route::input('id'),
		]);
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		if($this->has('user_id'))
		{
			$this->merge(['user_id' => GeneralHelpers::decode($this->input('user_id'))]);
		}
	}


}
