<?php

namespace App\Modules\Sales\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface AfterSalesVisitRepo
 * @package namespace App\Modules\Sales\Repositories\Contracts;
 */
interface AfterSalesVisitRepo extends RepositoryInterface {

	/**
	 *  Get Details based on conditions passed
	 *
	 * @param int|array  $primaryKey Mention primary_key value, array in case of composite primary key
	 * @param null|array $where
	 * @param null|array $columns
	 *
	 * @return Collection Return Records based on conditions
	 */
	public function getAfterSalesVisitDetail( $primaryKey = null, array $where = [], $columns = [] );

	/**
	 * Get After Sales Visit Details of Institute
	 *
	 * @param int   $instituteId Id of Institute
	 * @param array $columns     all, or Specific columns you want
	 *
	 * @return Collection
	 */
	public function getAfterSalesVisitDetailByInstituteId( $instituteId, $columns = [] );

	/**
	 * Search after sales visit entries. This method applies AfterSalesVisitSearchCrit criteria
	 *
	 * @param bool $paginate   output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo     number of the page
	 * @param int  $pageLength length of the page
	 *
	 * @return  LengthAwarePaginator|Collection
	 */
	public function searchAfterSalesVisit( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );

	/**
	 * Update after sales visit entry
	 *
	 * @param array $afterSalesVisitData Array of data to be updated
	 * @param int   $afterSalesVisitId   Id of after sales visit entry
	 *
	 * @return mixed
	 */
	public function updateAfterSalesVisit( $afterSalesVisitData, $afterSalesVisitId );

	/**
	 * Create new after sales visit Entry
	 *
	 * @param array $afterSalesVisitData Array of data to create after sales visit
	 *
	 * @return mixed
	 */
	public function createAfterSalesVisit( $afterSalesVisitData );

	/**
	 * Delete after sales visit entry
	 * @param $afterSalesVisitId
	 *
	 * @return mixed
	 */
	public function deleteAfterSalesVisit( $afterSalesVisitId );

	/**
	 * Get Available Designation
	 *
	 * @param string $term search term
	 *
	 * @return Collection
	 */
	public function getAvailableDesignations( $term = '' );
}