<?php

namespace App\Modules\Sales\Repositories\Criteria;

use App\Common\GeneralHelpers;
use App\Modules\Shared\Misc\ViewHelper;
use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class SalesTeamSearchCriteria
 * @package namespace App\Modules\Sales\Criteria;
 */
class SalesTeamSearchCrit implements CriteriaInterface {

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * RequestCriteria constructor
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param Builder             $model
	 * @param RepositoryInterface $repository
	 *
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		if ( $this->request->has('first_name') ) {
			$model->where('first_name', 'like', '%' . GeneralHelpers::clearParam($this->request->input('first_name'), PARAM_RAW_TRIMMED) . '%');
		}

		if ( $this->request->has('last_name') ) {
			$model->where('last_name', 'like', '%' . GeneralHelpers::clearParam($this->request->input('last_name'), PARAM_RAW_TRIMMED) . '%');
		}

		if ( $this->request->has('city_name') ) {
			$model->where('city_name', 'like', '%' . GeneralHelpers::clearParam($this->request->input('city_name'), PARAM_RAW_TRIMMED) . '%');
		}

		if ( $this->request->has('is_left') && $this->request->input('is_left') > ViewHelper::SELECT_OPTION_VALUE_ANY ) {
			$is_left = (int) $this->request->input('is_left') - 1;
			$model->where('is_left', '=', GeneralHelpers::clearParam($is_left, PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('parent_member_id') ) {
			$model->where('parent_member_id', '=', GeneralHelpers::clearParam($this->request->input('parent_member_id'), PARAM_RAW_TRIMMED));
		}

		return $model;
	}
}
