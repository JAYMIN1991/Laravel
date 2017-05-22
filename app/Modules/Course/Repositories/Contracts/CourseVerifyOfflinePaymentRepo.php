<?php

namespace App\Modules\Course\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CourseVerifyOfflinePaymentRepo
 * @package namespace App\Modules\Course\Repositories\Contracts;
 */
interface CourseVerifyOfflinePaymentRepo extends RepositoryInterface {

	/**
	 * Get Records for verify offline payment
	 *
	 * @param bool $pagination
	 * @param int  $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getVerifyOfflinePaymentRecords( $pagination = false, $offlinePaymentId = null );

	/**
	 *
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function canProceedVerifyOfflinePayment( $offlinePaymentId );

	/**
	 * @param $offlinePaymentId
	 * @param $instrumentProcessStatus
	 *
	 * @return mixed
	 */
	public function verifyOfflineCheckCleared( $offlinePaymentId, $instrumentProcessStatus );

	/**
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function verifyOfflineGetCouponUsage( $offlinePaymentId );

	/**
	 * @param $fieldsArray
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function updateCouponCode( $fieldsArray, $offlinePaymentId );

	/**
	 * @param $offlinePaymentId
	 * @param $returnCancel
	 * @param $reason
	 *
	 * @return mixed
	 */
	public function verifyOfflinePaymentReturnCancel( $offlinePaymentId, $returnCancel, $reason );
}
