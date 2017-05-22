<?php
namespace App\Modules\Sales\Repositories\Contracts;

use App\Modules\Sales\Repositories\SalesVisit;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface SalesVisitRepository
 * @package namespace App\Modules\Sales\Repositories;
 * @see     SalesVisit
 */
interface SalesVisitRepo extends RepositoryInterface {

	/**
	 * Get Available Designation
	 *
	 * @param $term
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getAvailableDesignations( $term = '');

	/**
	 * Returns list of SalesVisit by applying search criteria
	 *
	 * @param bool $paginate   true means paginate the output and false means output as collection
	 * @param int  $pageNo     number of the page
	 * @param int  $pageLength length of the page
	 *
	 * @return  LengthAwarePaginator|Collection
	 */
	public function search( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );

	/**
	 * Getting detail of sales visit
	 *
	 * @param int   $salesVisitId Id of sales visit
	 * @param array $where        array of where conditions
	 * @param array $columns      columns to fetch in result
	 *
	 * @return array|null Returns array of sales visit, null if not found
	 */
	public function getDetail( $salesVisitId, array $where = [], $columns = [] );

	/**
	 * Update Visit Entry
	 *
	 * @param array $data         Array of data to be update
	 * @param int   $salesVisitId Id of sales visit entry
	 * @param array $where        Array of condition
	 *
	 * @return mixed
	 */
	public function updateSalesVisit( $data, $salesVisitId, array $where = [] );

	/**
	 * Create new sales visit entry
	 *
	 * @param array $data Array of data to create sales visit
	 *
	 * @return mixed
	 */
	public function createSalesVisit( $data );

	/**
	 * Get Sales Visit details with Institute and Category
	 *
	 * @param int $salesVisitId Id for sales visit
	 *
	 * @return mixed
	 */
	public function getInstituteAndCategoryDetail( $salesVisitId );


	/**
	 * Check salesVisitId and inquiryId combination
	 *
	 * @param $salesVisitId
	 * @param $instInquiryId
	 *
	 * @return bool
	 */
	public function checkInquiryIdVisitIdCombination( $salesVisitId, $instInquiryId );

	/**
	 * Update acquisition details of sales visit
	 *
	 * @param int $salesVisitId id of the sales visit
	 * @param int $userId id of the user who is removing acquisition
	 * @param bool $acquisitionStatus  status of the acquisition
	 *
	 * @return bool status of the operation
	 */
	public function updateAcquisition($salesVisitId, $userId, $acquisitionStatus);

	/**
	 *  This function gives statistic for acquisition report
	 *
	 * @param array  $filterData array of filters
	 *               $filterData['ref_by'] array array of sales member_id
	 *               $filterData['date_from'] date date in format of input date format (d/m/Y)
	 *               $filterData['date_to'] date date in format of input date format (d/m/Y)
	 *               $filterData['date_range_on'] int field on which date range should be applied. it can be institute or user. @see App\Modules\Shared\Misc\AcquisitionReportViewHelper
	 *               $filterData['post_type'] int operator for post count. option can be 'greater then', 'less then', or 'equals to'. @see App\Modules\Shared\Misc\AcquisitionReportViewHelper
	 *               $filterData['post_value'] int count for total post
	 *               $filterData['course_user_id'] int user_id of institute
	 * @param bool $paginate output format can be 'paginator' or 'collection'. default is paginator
	 * @param int    $pageNo
	 * @param int    $pageLength
	 *
	 * @return  LengthAwarePaginator|Collection
	 * @see AcquisitionRequest @ report
	 */
	public function searchAcquisition( $filterData, $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );
}
