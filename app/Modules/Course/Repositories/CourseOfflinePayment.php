<?php

namespace App\Modules\Course\Repositories;

use App;
use App\Modules\Course\Repositories\Criteria\SearchOfflinePaymentCrit;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Flinnt\Repository\Criteria\RequestCriteria;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use Helper;

/**
 * Class CourseOfflinePayment
 * @package namespace App\Modules\Course\Repositories;
 */
class CourseOfflinePayment extends BaseRepository implements CourseOfflinePaymentRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'offline_payment_id';


	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_PAY_OFFLINE;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Get all offline payment records with search Criteria
	 *
	 * @param bool $pagination
	 * @param null $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentRecords( $pagination = false, $offlinePaymentId = null ) {
		$this->select([
			DB::raw('DISTINCT(' . TABLE_PAY_OFFLINE . '.offline_payment_id)'),
			DB::raw('GROUP_CONCAT(' . TABLE_PAY_COUPONS . '.coupon_code SEPARATOR "\n") `coupon_codes`'),
			DB::raw('GROUP_CONCAT(' . TABLE_PAY_COUPONS . '.coupon_code SEPARATOR ",") `report_coupon_codes`'),
			TABLE_USERS . '.user_id',
			TABLE_USERS . '.user_school_name',
			TABLE_COURSES . '.course_id',
			TABLE_COURSES . '.course_name',
			TABLE_PAY_OFFLINE . '.total_buyer',
			TABLE_PAY_OFFLINE . '.amount_paid',
			TABLE_PAY_OFFLINE . '.instrument_type',
			TABLE_PAY_OFFLINE . '.instrument_no',
			DB::raw('DATE_FORMAT(FROM_UNIXTIME(' . TABLE_PAY_OFFLINE . '.instrument_date),"%d/%m/%Y") `instrument_date`'),
			TABLE_PAY_OFFLINE . '.instrument_issuer_name',
			TABLE_PAY_OFFLINE . '.instrument_issuer_sub',
			TABLE_PAY_OFFLINE . '.remarks',
			TABLE_PAY_OFFLINE . '.is_coupon_generated',
			TABLE_PAY_OFFLINE . '.payment_status',
			TABLE_PAY_OFFLINE . '.payment_status_account'
		])
		     ->leftJoin(TABLE_PAY_COUPONS, TABLE_PAY_COUPONS . '.offline_payment_id', '=', TABLE_PAY_OFFLINE . '.offline_payment_id')
		     ->join(TABLE_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_PAY_OFFLINE . '.course_id')
		     ->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner')
		     ->groupBy(TABLE_PAY_OFFLINE . '.offline_payment_id');
		if ( $offlinePaymentId ) {
			$this->where(TABLE_PAY_OFFLINE . '.offline_payment_id', '=', $offlinePaymentId);
		} else {
			$this->orderBy(TABLE_PAY_OFFLINE . '.offline_payment_id', 'desc');
		}

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}

	/**
	 * @param $courseId
	 * @param $totalBuyer
	 *
	 * @return int
	 */
	public function getCourseCanUserSubscribe( $courseId, $totalBuyer ) {
		$result = $this->from(TABLE_COURSES)->select([
			'course_id',
			'course_public',
			'course_status',
			'course_is_free',
			DB::raw('IFNULL(course_enrollment_end_date, 0) `course_enrollment_end_date`'),
			'course_owner',
			'course_max_subscription',
			'course_plan_expired',
			'course_enabled',
			'course_end_date',
			'course_public_type_id',
			DB::raw('IFNULL(course_max_subscription, 0) `course_max_subscription`'),
			'course_paid_promotion',
			'course_paid_promo_max_subscription'
		])->where('course_id', '=', $courseId)->first();

		if ( empty($result) || empty($result['course_id']) ) {
			return COURSE_SUB_VALID_NO_COURSE;
		}
		// if validation filtered for subscription status or asked for all validations and course is not published
		if ( $result['course_status'] != COURSE_STATUS_PUBLISH ) {
			// return failure reason
			return COURSE_SUB_VALID_NOT_PUBLISHED;
		}
		// if validation filtered for course enable status or asked for all validations and course is not published
		if ( $result['course_enabled'] == 0 ) {
			// return failure reason
			return COURSE_SUB_VALID_DISABLED;
		}

		// if validation filtered for course owner plan status or asked for all validations and course owner's plan has been expired
		if ( $result['course_plan_expired'] == 1 ) {
			// return failure reason
			return COURSE_SUB_VALID_PLAN_EXPIRED;
		}
		// if validation filtered for course owner plan status or asked for all validations and course is free
		if ( $result['course_is_free'] == 1 ) {
			// return failure reason
			return COURSE_SUB_VALID_IS_FREE;
		}

		/* only consider learners in subscribed users count */
		$subscribedUsers = App::make(CourseRepo::class)->getEnrollmentCount($courseId, [USER_COURSE_ROLE_LEARNER]);

		if ( $result['course_paid_promotion'] ) {
			$maxSubscriptionLimit = $result['course_paid_promo_max_subscription'];
		} else {
			$maxSubscriptionLimit = $result['course_max_subscription'];
		}

		$remainingSubscription = $maxSubscriptionLimit - $subscribedUsers;

		// Only for timebound - if maximum subscription limit is defined at course and total no. of learners subscribed are equal to or higher than limit
		if ( in_array($result['course_public_type_id'], array(COURSE_TYPE_TIMEBOUND)) ) {

			if ( $maxSubscriptionLimit > 0 ) {
				if ( $remainingSubscription <= 0 ) {
					// return failure reason
					return COURSE_SUB_VALID_REMAINING_SUBSCRIPTION;
				}
				if ( $totalBuyer > $remainingSubscription ) {
					// return failure reason
					return COURSE_SUB_VALID_INSUFFICIENT_SUBSCRIPTION;
				}
			}
		}

		// if owner has mentioned last enrollment date and it is already passed or ends on current date
		if ( $result['course_enrollment_end_date'] > 0 && $result['course_enrollment_end_date'] < Helper::datetimeToTimestamp() ) {
			// return failure reason
			return COURSE_SUB_VALID_DATE_EXPIRED;
		}

		// if owner has mentioned last enrollment date and it is already passed or ends on current date
		if ( $result['course_end_date'] > 0 && $result['course_end_date'] < Helper::datetimeToTimestamp() ) {
			// return failure reason
			return COURSE_SUB_VALID_END_DATE;
		}

		return COURSE_SUB_VALID_SUCCESS;
	}

	/**
	 * Get single record of offline payment
	 *
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentDetails( $offlinePaymentId ) {
		$result = $this->select([
			'offline_payment_id',
			'institute_id',
			'course_id',
			'total_buyer',
			'is_instrument_processed',
			DB::raw('REPLACE(FORMAT(amount_paid, 2), ",", "") `amount_paid`'),
			'instrument_no',
			DB::raw('DATE_FORMAT(FROM_UNIXTIME(instrument_date), "%d/%m/%Y") `instrument_date`'),
			'instrument_issuer_name',
			'instrument_issuer_sub',
			'remarks',
			'is_coupon_generated',
			'member_id',
			'billing_name',
			'billing_address',
			'billing_city',
			'billing_state',
			'billing_pincode',
			'billing_phone',
			'billing_email'
		])->where('offline_payment_id', '=', $offlinePaymentId)->first();

		return $this->parserResult($result);
	}

	/**
	 * Get course commission value using course type and institute id
	 *
	 * @param $courseType
	 * @param $instituteId
	 *
	 * @return int
	 */
	public function getCourseCommission( $courseType, $instituteId ) {
		$commission = 0;

		$courseTypeCommissionDetails = $this->getCourseTypeCommission($courseType);

		if ( ! empty($courseTypeCommissionDetails[0]['commission_id']) ) {
			$commission = $courseTypeCommissionDetails[0]['commission_percent'];
		}

		/* get commission percent from pay_commission_discounts table according to commission id of pay_commissions table */
		$commissionDiscountDetails = $this->getCommissionDiscount($courseTypeCommissionDetails[0]['commission_id'], $instituteId);

		/* if commission exists in pay_commission_discounts table, then it will be new commission */
		if ( ! empty($commissionDiscountDetails[0]['comm_discount_id']) ) {
			$commission = $commissionDiscountDetails[0]['applicable_perc'];
		}

		return $commission;
	}

	/**
	 * Get course commission by course type
	 *
	 * @param $courseType
	 *
	 * @return mixed
	 */
	protected function getCourseTypeCommission( $courseType ) {
		$result = $this->from(TABLE_PAY_COMMISSIONS)
		               ->select(['commission_id', 'commission_percent'])
		               ->where('course_type', '=', $courseType)
		               ->where('is_applicable', '=', '1')
		               ->get();

		return $this->parserResult($result);
	}

	/**
	 * Get course commission discount using commission and institute id
	 *
	 * @param $commissionId
	 * @param $instituteId
	 *
	 * @return mixed
	 */
	protected function getCommissionDiscount( $commissionId, $instituteId ) {
		$result = $this->from(TABLE_PAY_COMMISSION_DISCOUNTS)
		               ->select(['comm_discount_id', 'applicable_perc'])
		               ->where('commission_id', '=', $commissionId)
		               ->where('user_id', '=', $instituteId)
		               ->where('is_applicable', '=', '1')
		               ->get();

		return $this->parserResult($result);
	}

	/**
	 * Create new record
	 *
	 * @param $offlinePaymentArray
	 *
	 * @return mixed
	 */
	public function createOfflinePayment( $offlinePaymentArray ) {
		return $this->create($offlinePaymentArray);
	}

	/**
	 * Update offline payment record by offline payment id
	 *
	 * @param $offlinePaymentRecordArray
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function updateOfflinePaymentRecord( $offlinePaymentRecordArray, $offlinePaymentId ) {
		return $this->update($offlinePaymentRecordArray, $offlinePaymentId);
	}

	/**
	 * Delete record
	 *
	 * @param $offlinePaymentId
	 *
	 * @return int
	 */
	public function destroyOfflinePaymentRecord( $offlinePaymentId ) {
		return $this->where('offline_payment_id', '=', $offlinePaymentId)->delete();
	}

	/**
	 * Get list of course for offline payment by institute id
	 *
	 * @param $instituteId for selected instutute
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentCoursesByInstituteId( $instituteId ) {
		$result = $this->select([
			DB::raw('DISTINCT(' . TABLE_PAY_OFFLINE . '.course_id) id'),
			TABLE_COURSES . '.course_name'
		])
		               ->join(TABLE_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_PAY_OFFLINE . '.course_id')
		               ->where(TABLE_COURSES . '.course_owner', '=', $instituteId)
		               ->orderBy(TABLE_COURSES . '.course_name')
		               ->get();

		return $this->parserResult($result);
	}

	/**
	 * Get all records of verify offline payment
	 *
	 * @param bool $pagination
	 *
	 * @return mixed
	 */
	public function getVerifyOfflinePaymentRecords( $pagination = false ) {
		$this->select([
			DB::raw('DISTINCT(' . TABLE_PAY_OFFLINE . '.offline_payment_id)'),
			DB::raw('GROUP_CONCAT(' . TABLE_PAY_COUPONS . '.coupon_code SEPARATOR "\n") `coupon_codes`'),
			DB::raw('GROUP_CONCAT(' . TABLE_PAY_COUPONS . '.coupon_code SEPARATOR ",") `report_coupon_codes`'),
			TABLE_USERS . '.user_school_name',
			TABLE_USERS . '.user_id',
			TABLE_COURSES . '.course_name',
			TABLE_COURSES . '.course_id',
			TABLE_COURSES . '.course_owner',
			TABLE_PAY_OFFLINE . '.total_buyer',
			TABLE_PAY_OFFLINE . '.amount_paid',
			TABLE_PAY_OFFLINE . '.instrument_type',
			TABLE_PAY_OFFLINE . '.instrument_no',
			DB::raw('DATE_FORMAT(FROM_UNIXTIME(' . TABLE_PAY_OFFLINE . '.instrument_date), "%d/%m/%Y") `instrument_date`'),
			TABLE_PAY_OFFLINE . '.instrument_issuer_name',
			TABLE_PAY_OFFLINE . '.instrument_issuer_sub',
			TABLE_PAY_OFFLINE . '.remarks',
			TABLE_PAY_OFFLINE . '.is_coupon_generated',
			TABLE_PAY_OFFLINE . '.is_instrument_processed',
			TABLE_PAY_OFFLINE . '.payment_status',
			TABLE_PAY_OFFLINE . '.payment_status_account',
			DB::raw(TABLE_PAY_OFFLINE_BUYER_INVOICES . '.invoice_filename  `offline_buyer_invoice_filename`'),
			DB::raw(TABLE_PAY_OFFLINE_SELLER_INVOICES . '.invoice_filename  `offline_seller_invoice_filename`')
		])
		     ->leftJoin(TABLE_PAY_COUPONS, TABLE_PAY_COUPONS . '.offline_payment_id', '=', TABLE_PAY_OFFLINE . '.offline_payment_id')
		     ->leftJoin(TABLE_PAY_OFFLINE_SELLER_INVOICES, TABLE_PAY_OFFLINE_SELLER_INVOICES . '.offline_payment_id', '=', TABLE_PAY_OFFLINE . '.offline_payment_id')
		     ->leftJoin(TABLE_PAY_OFFLINE_BUYER_INVOICES, TABLE_PAY_OFFLINE_BUYER_INVOICES . '.offline_payment_id', '=', TABLE_PAY_OFFLINE . '.offline_payment_id')
		     ->join(TABLE_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_PAY_OFFLINE . '.course_id')
		     ->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner')
		     ->where(TABLE_PAY_OFFLINE . '.is_deleted', '=', '0')
		     ->groupBy(TABLE_PAY_OFFLINE . '.offline_payment_id', TABLE_USERS . '.user_school_name', TABLE_USERS . '.user_id', TABLE_COURSES . '.course_name', TABLE_COURSES . '.course_id', TABLE_COURSES . '.course_owner', TABLE_PAY_OFFLINE . '.total_buyer', TABLE_PAY_OFFLINE . '.amount_paid', TABLE_PAY_OFFLINE . '.instrument_type', TABLE_PAY_OFFLINE . '.instrument_no', TABLE_PAY_OFFLINE . '.instrument_date', TABLE_PAY_OFFLINE . '.instrument_issuer_name', TABLE_PAY_OFFLINE . '.instrument_issuer_sub', TABLE_PAY_OFFLINE . '.remarks', TABLE_PAY_OFFLINE . '.is_coupon_generated', TABLE_PAY_OFFLINE . '.is_instrument_processed', TABLE_PAY_OFFLINE . '.payment_status', TABLE_PAY_OFFLINE . '.payment_status_account', TABLE_PAY_OFFLINE_BUYER_INVOICES . '.invoice_filename', TABLE_PAY_OFFLINE_SELLER_INVOICES . '.invoice_filename')
		     ->orderBy(TABLE_PAY_OFFLINE . '.offline_payment_id', 'desc');

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}
}
