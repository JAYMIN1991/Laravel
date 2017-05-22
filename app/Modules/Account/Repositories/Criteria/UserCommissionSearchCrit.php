<?php

namespace App\Modules\Account\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class UserCommissionSearchCrit
 * @package namespace App\Modules\Account\Repositories\Criteria;
 */
class UserCommissionSearchCrit extends AbstractCriteria {

	protected $request;

	/**
	 * UserCommissionSearchCrit constructor.
	 *
	 * @param Request $request
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
		$instituteId = GeneralHelpers::decode($this->request->input('institute_id'));
		$courseTypeId = $this->request->input('course_type');
		$commissionRangeId = $this->request->input('commission_range');
		$commissionValue = $this->request->input('commission_value');
		$selectIsApplicable = $this->request->input('select_is_applicable');

		if ( $instituteId > 0 ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$model->where(TABLE_PAY_COMMISSION_DISCOUNT . '.user_id', GeneralHelpers::clearParam($instituteId, PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('course_type') ) {

			if ( $courseTypeId == 2 ) {
				$courseTypeId = '1';
			} else if ( $courseTypeId == 3 ) {
				$courseTypeId = '2';
			}
			/** @noinspection PhpUndefinedMethodInspection */
			$model->where(TABLE_PAY_COMMISSION_DISCOUNT . '.commission_id', GeneralHelpers::clearParam($courseTypeId, PARAM_INT));
		}

		if ( $commissionRangeId || $commissionValue ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$model->where(TABLE_PAY_COMMISSION_DISCOUNT . '.applicable_perc', $commissionRangeId, $commissionValue);
		}

		if ( $selectIsApplicable > 0 ) {
			if ( $selectIsApplicable == 2 ) {
				$selectIsApplicableValue = 0;
			} else {
				$selectIsApplicableValue = 1;
			}
			/** @noinspection PhpUndefinedMethodInspection */
			$model->where(TABLE_PAY_COMMISSION_DISCOUNT . '.is_applicable', GeneralHelpers::clearParam($selectIsApplicableValue, PARAM_RAW_TRIMMED));
		}

		return $model;
	}
}
