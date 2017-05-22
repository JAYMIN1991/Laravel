<?php

namespace App\Modules\Course\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CourseOfflinePaymentRepo
 * @package namespace App\Modules\Course\Repositories\Contracts;
 */
interface CourseOfflinePaymentRepo extends RepositoryInterface
{

	/**
	 * Get offline payment records collection
	 * @param bool $pagination
	 * @param null $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentRecords( $pagination = false, $offlinePaymentId = null);

	/**
	 * Get institute wise course can subscribe validation
	 * @param $courseId
	 * @param $totalBuyer
	 *
	 * @return boolean
	 */
	public function getCourseCanUserSubscribe( $courseId, $totalBuyer);

	/**
	 * Get Offline Payment Courses name list by institute Id
	 * @param $instituteId for institute id
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentCoursesByInstituteId( $instituteId);

	/**
	 * @param $courseType
	 * @param $instituteId
	 *
	 * @return mixed
	 */
	public function getCourseCommission( $courseType, $instituteId);


	/**
	 * Create new offline payment record
	 * @param $offlinePaymentArray
	 *
	 * @return mixed
	 */
	public function createOfflinePayment( $offlinePaymentArray);

	/**
	 * Get perticular offline payment details by offline payment id
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function getOfflinePaymentDetails( $offlinePaymentId);

	/**
	 * Update records
	 * @param $offlinePaymentRecordArray
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function updateOfflinePaymentRecord( $offlinePaymentRecordArray, $offlinePaymentId);

	/**
	 * Delete records using $offlinePaymentId
	 * @param $offlinePaymentId
	 *
	 * @return mixed
	 */
	public function destroyOfflinePaymentRecord( $offlinePaymentId);

	/**
	 * Get verify offline payment records
	 * @param bool $pagination
	 *
	 * @return mixed
	 */
	public function getVerifyOfflinePaymentRecords( $pagination = false);
}
