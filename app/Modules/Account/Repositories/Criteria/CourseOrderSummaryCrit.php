<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-5
 * Date: 18/2/17
 * Time: 6:23 PM
 */

namespace App\Modules\Account\Repositories\Criteria;

use App\Common\GeneralHelpers;
use DB;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Helper;
use Illuminate\Http\Request;

/**
 * Class CourseOrderSummaryCrit
 * @package App\Modules\Account\Repositories\Criteria
 */
class CourseOrderSummaryCrit extends AbstractCriteria {

	protected $request;

	/**
	 * CourseOrderSummaryCrit constructor.
	 *
	 * @param \Illuminate\Http\Request $request
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
	function apply( $model, RepositoryInterface $repository ) {

		$instituteId = GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->input('institute_id'), PARAM_RAW_TRIMMED));
		$courseId = GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->input('course-list'), PARAM_RAW_TRIMMED));
		$orderStatus = GeneralHelpers::clearParam($this->request->input('order_status'), PARAM_RAW_TRIMMED);
		$isPaidStatus = GeneralHelpers::clearParam($this->request->input('is_paid'), PARAM_RAW_TRIMMED);
		$dataFrom = GeneralHelpers::clearParam($this->request->input('date_from'), PARAM_RAW_TRIMMED);
		$dateTo = GeneralHelpers::clearParam($this->request->input('date_to'), PARAM_RAW_TRIMMED);
		$orderId = GeneralHelpers::clearParam($this->request->input('order_id'), PARAM_RAW_TRIMMED);

		if ( $this->request->has('institute_id') ) {
			$model->where(TABLE_COURSES . '.course_owner', '=', $instituteId);
		}

		if ( $this->request->has('course-list') ) {
			$model->where(TABLE_COURSES . '.course_id', '=', $courseId);
		}

		if ( $this->request->has('order_status') ) {
			$model->where(TABLE_PAY_TRANSACTIONS . '.trans_status', '=', $orderStatus);
		}

		if ( $this->request->has('is_paid') ) {
			if ( $isPaidStatus == '1' ) {
				$model->where(TABLE_PAY_SELLER_INVOICES . '.is_paid_seller', '=', 1);
			} else {
				$model->where(TABLE_PAY_SELLER_INVOICES . '.is_paid_seller', '=', 'NULL');
				$model->orwhere(TABLE_PAY_SELLER_INVOICES . '.is_paid_seller', '=', '0');
			}
		}

		if ( $this->request->has('date_from') && ! $this->request->has('date_to') ) {
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_PAY_TRANSACTIONS . '.trans_dt))'), array(
				DB::raw(GeneralHelpers::saveFormattedDate($dataFrom)),
				DB::raw((string) Helper::getDate(trans('shared::config.mysql_date_format')))
			));
		}

		if ( ! $this->request->has('date_from') && $this->request->has('date_to') ) {
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_PAY_TRANSACTIONS . '.trans_dt))'), array(
				DB::raw('0000-00-00'),
				DB::raw(GeneralHelpers::saveFormattedDate($dateTo))
			));
		}

		if ( $this->request->has('date_from') && $this->request->has('date_to') ) {
			$model->whereBetween(DB::raw('DATE(FROM_UNIXTIME(' . TABLE_PAY_TRANSACTIONS . '.trans_dt))'), array(
				DB::raw(GeneralHelpers::saveFormattedDate($dataFrom)),
				DB::raw(GeneralHelpers::saveFormattedDate($dateTo))
			));
		}

		if ( $this->request->has('order_id') ) {
			$model->where(TABLE_PAY_TRANSACTIONS . '.trans_id', 'LIKE', '%' . $orderId . '%');
		}

		return $model;
	}
}