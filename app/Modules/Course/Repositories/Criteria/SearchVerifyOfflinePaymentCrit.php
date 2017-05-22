<?php

namespace App\Modules\Course\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class SearchVerifyOfflinePaymentCrit
 * @package namespace App\Modules\Course\Repositories\Criteria;
 */
class SearchVerifyOfflinePaymentCrit extends AbstractCriteria {

	protected $request;

	/**
	 * SearchVerifyOfflinePaymentCrit constructor.
	 *
	 * @param \Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		if ( $this->request->has('coupon_status') ) {
			$model->where(TABLE_PAY_OFFLINE . '.is_coupon_generated', '=', GeneralHelpers::clearParam($this->request->get('coupon_generated'), PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('check_cleared') ) {
			$model->where(TABLE_PAY_OFFLINE . '.is_instrument_processed', '=', GeneralHelpers::clearParam($this->request->get('check_cleared'), PARAM_RAW_TRIMMED));
		}

		return $model;
	}
}
