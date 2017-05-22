<?php
namespace App\Modules\Sales\Repositories\Contracts;

use App\Modules\Sales\Repositories\InstInquiry;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface InstInquiryRepository
 * @package namespace App\Modules\Sales\Repositories;
 * @see     InstInquiry
 */
interface InstInquiryRepo extends RepositoryInterface {

	/**
	 * Get Non Acquired Institute List
	 * @return Collection Returns Collection to fill in drop-down
	 */
	public function getListForNonAcquiredInstInquiry();

	/**
	 * Get list of  All Institute
	 * @return Collection Collection to fill in drop-down
	 */
	public function getList();

	/**
	 * Get institute list which are not created via old institute list page
	 *
	 * @return Collection Returns Collection to fill in drop-down
	 */
	public function getListOfInstituteNotAcquiredFromInstituteList();

	/**
	 * Get institute details
	 *
	 * @param int|null $instInquiryId Institute inquiry id
	 *
	 * @param array    $where         array of where conditions
	 * @param array    $columns       columns to fetch in result
	 * @param string   $method        Method to be used while fetching data
	 *
	 * @return mixed Returns details of institute inquiry
	 */
	public function getDetail( $instInquiryId = null, array $where = [], $columns = [], $method = 'get' );

	/**
	 * Get Available Cities from Institute Inquiry
	 *
	 * @param string $term Supply term to get matching city names
	 *
	 * @return mixed  Returns all matching cities
	 */
	public function getAvailableCities( $term = '' );

	/**
	 * Get Institute Details based on Existing Inquiry
	 *
	 * @param int $instInquiryId Id of inst_inquiry
	 *
	 * @return mixed Returns details of the institute which is not acquired yet
	 */
	public function getDetailOfNotAcquiredInstitute( $instInquiryId );

	/**
	 * Update Inquiry
	 *
	 * @param array $inquiryData Array of data to be updated
	 * @param int   $inquiryId   Id of sales visit entry
	 *
	 * @return \stdClass|array|null Returns updated inquiry entry
	 */
	public function updateInquiry( $inquiryData, $inquiryId );

	/**
	 * Create Inquiry
	 *
	 * @param array $inquiryData Array of data to create
	 *
	 * @return mixed
	 */
	public function createInquiry( $inquiryData );

	/**
	 * Function to check whether institute is acquired or not
	 *
	 * @param int $instInquiryId Id of the institute inquiry
	 *
	 * @return bool status of institute acquisition
	 */
	public function isInstituteAcquired( $instInquiryId );

	/**
	 * Removes acquisition details of institute
	 *
	 * @param int $instInquiryId Id of the institute inquiry
	 * @param int $userId        Id of the user who is removing acquisition
	 *
	 * @return bool status of the operation
	 */
	public function removeInstituteAcquisition( $instInquiryId, $userId );

	/**
	 * Acquire the institute
	 *
	 * @param int $instInquiryId        Id of the institute inquiry
	 * @param int $convertedInstituteId Id of the converted institute
	 * @param int $memberId             Id of the member who is updating acquisition
	 * @param int $userId               Id of the user who is updating acquisition
	 *
	 * @return bool status of the operation
	 */
	public function acquireInstitute( $instInquiryId, $convertedInstituteId, $memberId, $userId );

	/**
	 * Get institute details
	 *
	 * @param int|null $instInquiryId Institute inquiry id
	 * @param array    $where array of where conditions
	 * @param array    $columns columns to fetch in result
	 *
	 * @return array|Collection Returns details of institute inquiry
	 */
	public function  getDetailWithLatestVisitDetails( $instInquiryId = null, array $where = [], $columns = []);
}
