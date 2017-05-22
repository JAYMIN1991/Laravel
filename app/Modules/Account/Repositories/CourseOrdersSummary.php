<?php

namespace App\Modules\Account\Repositories;

use App\Modules\Account\Repositories\Criteria\CourseOrderSummaryCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Flinnt\Repository\Criteria\RequestCriteria;
use App\Modules\Account\Repositories\Contracts\CourseOrdersSummaryRepo;

/**
 * Class CourseOrdersSummary
 * @package namespace App\Modules\Account\Repositories;
 * @see     CourseOrdersSummary
 */
class CourseOrdersSummary extends BaseRepository implements CourseOrdersSummaryRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'trans_id';

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * get course order summary result
	 *
	 * @param null $pagination
	 */
	public function getCourseOrderSummaryResult( $pagination = null ) {
		$this->pushCriteria(app(CourseOrderSummaryCrit::class));
		$this->from($this->model())
		     ->select([
			     DB::raw(TABLE_PAY_TRANSACTIONS . '.trans_id'),
			     DB::raw('DATE_FORMAT(FROM_UNIXTIME(' . TABLE_PAY_TRANSACTIONS . '.trans_dt), "%d/%m/%Y") trans_dt'),
			     DB::raw(TABLE_COURSES . '.course_name'),
			     DB::raw(TABLE_COURSES . '.course_owner'),
			     DB::raw('FORMAT(' . TABLE_PAY_TRANSACTIONS . '.total_invoice,4) AS total_invoice'),
			     DB::raw('FORMAT(' . TABLE_PAY_TRANSACTIONS . '.total_commission,4) AS total_commission'),
			     DB::raw('FORMAT(' . TABLE_PAY_TRANSACTIONS . '.total_charges,4) AS total_charges'),
			     DB::raw('FORMAT(' . TABLE_PAY_TRANSACTIONS . '.total_seller_invoice,4) AS total_seller_invoice'),
			     DB::raw(TABLE_PAY_TRAN_PAYMENT . '.payment_mode'),
			     DB::raw(TABLE_PAY_BUYER_INVOICES . '.buyer_id'),
			     DB::raw(TABLE_PAY_BUYER_INVOICES . '.seller_id'),
			     DB::raw(TABLE_PAY_TRANSACTIONS . '.trans_status'),
			     DB::raw(TABLE_PAY_SELLER_INVOICES . '.is_paid_seller'),
			     DB::raw('IFNULL(' . TABLE_PAY_BUYER_INVOICES . '.invoice_filename,0) buyer_invoice_filename'),
			     DB::raw('IFNULL(' . TABLE_PAY_SELLER_INVOICES . '.invoice_filename,0) seller_invoice_filename'),
			     DB::raw('CASE WHEN ' . TABLE_PAY_SELLER_INVOICES . '.is_paid_seller = 1 THEN "Yes" ELSE "No" END is_paid_seller_label'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_INITIALIZED . ',"Initialized"'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_IN_SESSION . ',"In Session"'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_PROCESSING . ',"Processing"'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_CANCELLED . ',"Cancelled"'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_FAILED . ',"Failed"'),
			     DB::raw('IF(' . TABLE_PAY_TRANSACTIONS . '.trans_status=' . TRAN_STATUS_COMPLETED . ',"Completed","N/A" ) ) ) ) ) ) trans_status_label'),
			     DB::raw('CASE WHEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_name IS NOT NULL THEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_name ELSE CONCAT_WS(" ",' . TABLE_USERS . '.user_firstname, ' . TABLE_USERS . '.user_lastname) END  billing_name'),
			     DB::raw('CASE WHEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_phone != 0 AND ' . TABLE_PAY_BUYER_INVOICES . '.billing_phone IS NOT NULL THEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_phone ELSE IF(' . TABLE_USERS . '.user_acc_closed = 1, "",' . TABLE_USERS . '.user_mobile) END  billing_phone'),
			     DB::raw('CASE WHEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_email IS NOT NULL THEN ' . TABLE_PAY_BUYER_INVOICES . '.billing_email ELSE IF(' . TABLE_USERS . '.user_acc_closed = 1, "", ' . TABLE_USERS . '.user_email) END  billing_email')
		     ])
		     ->leftJoin(TABLE_PAY_TRAN_ITEMS, TABLE_PAY_TRAN_ITEMS . '.trans_id', '=', TABLE_PAY_TRANSACTIONS . '.trans_id')
		     ->leftJoin(TABLE_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_PAY_TRAN_ITEMS . '.item_id')
		     ->leftJoin(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_PAY_TRANSACTIONS . '.user_id')
		     ->leftJoin(TABLE_PAY_TRAN_PAYMENT, TABLE_PAY_TRAN_PAYMENT . '.trans_id', '=', TABLE_PAY_TRANSACTIONS . '.trans_id')
		     ->leftJoin(TABLE_PAY_BUYER_INVOICES, TABLE_PAY_BUYER_INVOICES . '.trans_id', '=', TABLE_PAY_TRANSACTIONS . '.trans_id')
		     ->leftJoin(TABLE_PAY_SELLER_INVOICES, TABLE_PAY_SELLER_INVOICES . '.trans_id', '=', TABLE_PAY_TRANSACTIONS . '.trans_id')
		     ->where(TABLE_PAY_TRANSACTIONS . '.is_online', '=', '1')
		     ->orderBy(TABLE_PAY_TRANSACTIONS . '.trans_dt', 'DESC');
		// keep this code
		//dd($this->toSql($this));

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}

	/**
	 * get selected table
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_PAY_TRANSACTIONS;
	}
}
