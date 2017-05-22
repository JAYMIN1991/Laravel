<?php

namespace App\Modules\Sales\Http\Requests\PostVisit;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use Illuminate\Foundation\Http\FormRequest;
use Session;

/**
 * Class IndexRequest
 * @package App\Modules\Sales\Http\Requests\PostVisit
 */
class IndexRequest extends FormRequest {

	/**
	 * @var array $validVisitBy List of valid member_ids, filled from database
	 */
	private $validVisitBy = [];

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
			'visit_by'        => 'sometimes|numeric|in:' . implode(',', $this->validVisitBy),
			'institute'       => 'sometimes|numeric|institute',
			'visit_date_from' => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"',
			'visit_date_to'   => 'sometimes|date_format:' . trans('shared::config.validation_rule_date_format') . '|before:"tomorrow"'
		];
	}

	/**
	 * Prepare the data for validation
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		$this->validVisitBy = App::make(SalesTeamRepo::class)
		                         ->getListByUserId(Session::get('user_id'))
		                         ->keys()
		                         ->toArray();

		if ( $this->has('institute') ) {
			$this->merge(['institute' => GeneralHelpers::decode($this->input('institute'))]);
		}
	}
}

