<?php

namespace App\Modules\Course\Repositories;

use App;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use Flinnt\Repository\Eloquent\BaseRepository;
use Flinnt\Repository\Criteria\RequestCriteria;
use App\Modules\Course\Repositories\Contracts\CourseVerifyOfflinePaymentRepo;
use GuzzleHttp\Exception\ClientException;
use Session;

/**
 * Class CourseVerifyOfflinePayment
 * @package namespace App\Modules\Course\Repositories;
 */
class CourseVerifyOfflinePayment extends BaseRepository implements CourseVerifyOfflinePaymentRepo {

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
	 * @param bool $pagination
	 * @param null $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getVerifyOfflinePaymentRecords( $pagination = false, $offlinePaymentId = null ) {
		$result = App::make(CourseOfflinePaymentRepo::class)->getOfflinePaymentRecords($pagination);

		return $result;
	}

	/**
	 * @param $offlinePaymentId
	 * @param $instrumentProcessStatus
	 *
	 * @return bool|mixed
	 */
	public function verifyOfflineCheckCleared( $offlinePaymentId, $instrumentProcessStatus ) {
		$transStatus = false;

		if ( $this->canProceedVerifyOfflinePayment($offlinePaymentId) ) {
			$updateFieldsArray = array();
			$updateFieldsArray['is_instrument_processed'] = $instrumentProcessStatus;
			$updateFieldsArray['payment_status'] = OFFLINE_PAY_STATUS_CONFIRMED; // set payment status confirmed
			$updateFieldsArray['payment_status_account'] = 1; // set payment status account

			$transStatus = $this->update($updateFieldsArray, $offlinePaymentId);
		}

		return $transStatus;
	}

	/**
	 * @param $offlinePaymentId
	 *
	 * @return bool
	 */
	public function canProceedVerifyOfflinePayment( $offlinePaymentId ) {
		$result = $this->select(['payment_status', 'payment_status_account', 'is_deleted'])
		               ->where('offline_payment_id', '=', $offlinePaymentId)
		               ->first();

		if ( ! in_array($result['payment_status'], array(
				OFFLINE_PAY_STATUS_CANCELLED,
				OFFLINE_PAY_STATUS_INSTRUMENT_INVALID
			)) && ! $result['is_deleted']
		) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $fieldsArray
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function updateCouponCode( $fieldsArray, $offlinePaymentId ) {
		$result = $this->update($fieldsArray, $offlinePaymentId);

		return $result;
	}

	/**
	 * @param $offlinePaymentId
	 * @param $returnCancel
	 * @param $reason
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function verifyOfflinePaymentReturnCancel( $offlinePaymentId, $returnCancel, $reason ) {
		$tranStatus = false;

		try {
			$couponUsageDetails = array();
			$couponUsageDetails = $this->verifyOfflineGetCouponUsage($offlinePaymentId);

			if ( $this->canProceedVerifyOfflinePayment($offlinePaymentId) ) {
				$couponUsageDetails['usage_count_list'] = array();
				/* if coupon is not used anywhere then and then go ahead */
				if ( ! in_array("1", $couponUsageDetails['usage_count_list']) ) {

					/* delete coupon from pay_coupons and pay_coupon_courses table if not used in any course */
					if ( ! empty($couponUsageDetails['coupon_id_list']) ) {
						$tranStatus = $this->deleteCouponsAfterReturnCancel($offlinePaymentId, $couponUsageDetails["coupon_id_list"]);
					}

					if ( $tranStatus ) {
						$verifyPaymentStatus = $this->setVerifyPaymentStatusAccount($offlinePaymentId, $returnCancel, $reason);
						if ( $verifyPaymentStatus ) {
							Session::flash('message', trans('course.verify_offline.common.success'));
							$tranStatus = true;
						}
					}
				} else {

					if ( empty($couponUsageDetails["coupon_id_list"]) ) {

						$tranStatus = $this->setVerifyPaymentStatusAccount($offlinePaymentId, $returnCancel, $reason);

						if ( $tranStatus ) {
							Session::flash('message', trans('course.verify_offline.common.success'));
						}

					} else {
						/* if coupon used any where then return false */
						Session::flash('message', trans('course.verify_offline.common.coupon_used'));
						$tranStatus = false;
					}
				}
			} else {
				/* if coupon used any where then return false */
				Session::flash('message', trans('course.verify_offline.common.error_updating'));
				$tranStatus = false;
			}
		} catch ( ClientException $e ) {
			// Insert DB log for exception
			\DBLog::save(LOG_MODULE_COURSE_VERIFY_OFFLINE_PAYMENT, $offlinePaymentId, 'UpdateFail', \Request::getRequestUri(), \Request::get('auth_user')['user_id'], $e->getMessage());
			Session::flash('message', trans('course.verify_offline.common.fail'));
			$tranStatus = false;
		}

		if ( $tranStatus ) {
			echo json_encode(["stat" => 1]);
		} else {
			echo json_encode(["stat" => 0]);
		}
	}

	/**
	 * @param $offlinePaymentId
	 *
	 * @return array
	 */
	public function verifyOfflineGetCouponUsage( $offlinePaymentId ) {
		$result = $this->from(TABLE_PAY_COUPONS)
		               ->select(['coupon_id', 'usage_count'])
		               ->where('offline_payment_id', '=', $offlinePaymentId)
		               ->get();

		$usageCountList = array();
		$couponIdList = array();
		$finalArray = array();

		if ( ! $result->isEmpty() ) {
			$i = 0;

			while ( $i < count($result) ) {
				$usageCountList[] = $result[$i]['usage_count'];
				$couponIdList[] = $result[$i]['coupon_id'];
				$i++;
			}

			$finalArray['usage_count_list'] = $usageCountList;
			$finalArray['coupon_id_list'] = $couponIdList;
		}

		return $finalArray;
	}

	/**
	 * @param $offlinePaymentId
	 * @param $couponIdList
	 *
	 * @return bool
	 */
	protected function deleteCouponsAfterReturnCancel( $offlinePaymentId, $couponIdList ) {
		$tranStatus = false;

		if ( ! empty($offlinePaymentId) && ! empty($couponIdList) ) {
			/* if coupon is not used anywhere then and then go ahead */
			// Keep this code
			//$couponIdList = implode(",", $couponIdList);
			$deleteCourseCoupons = $this->from(TABLE_PAY_COUPON_COURSES);
			$deleteCourseCoupons->whereIn('coupon_id', $couponIdList);
			$tranStatus = $deleteCourseCoupons->delete();

			if ( $tranStatus ) {

				$deleteCoupons = $this->from(TABLE_PAY_COUPONS)->whereIn('coupon_id', $couponIdList)->delete();

				if ( $deleteCoupons ) {
					$tranStatus = true;
				}

			} else {
				$tranStatus = false;
			}

		} else {
			$tranStatus = false;
		}

		return $tranStatus;
	}

	/**
	 * @param $offlinePaymentId
	 * @param $returnCancel
	 * @param $reason
	 *
	 * @return bool|mixed
	 */
	protected function setVerifyPaymentStatusAccount( $offlinePaymentId, $returnCancel, $reason ) {
		$arrayFieldsValue = array();

		$arrayFieldsValue['payment_status'] = $returnCancel;
		$arrayFieldsValue['payment_status_account'] = 1;
		$arrayFieldsValue['payment_cancel_reason'] = $reason;
		$arrayFieldsValue['user_ip'] = \Helper::getIPAddress();
		$arrayFieldsValue['updated'] = time();
		$arrayFieldsValue['updated_user'] = \Session::get('user_id');
		$arrayFieldsValue['device_type'] = 'BACKOFFICE';
		// Update data to existing entry
		$tranStatus = $this->update($arrayFieldsValue, $offlinePaymentId);

		if ( $tranStatus ) {
			$arrFieldsValue = array();

			$arrFieldsValue['offline_payment_id'] = $offlinePaymentId;
			$arrFieldsValue['payment_status'] = $returnCancel;
			$arrFieldsValue['status_dt'] = time();
			$arrFieldsValue['user_id'] = \Session::get('user_id');
			$arrFieldsValue['user_ip'] = \Helper::getIPAddress();
			$arrFieldsValue['device_type'] = 'BACKOFFICE';
			// Insert payment details to history
			$tranStatus = $this->from(TABLE_PAY_OFFLINE_STATUS_HISTORY)->create($arrFieldsValue);
		}

		// Return updated status
		return $tranStatus;
	}
}
