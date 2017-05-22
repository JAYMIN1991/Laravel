<?php

namespace App\Modules\Sales\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface SalesTeamRepository
 * @package namespace App\Modules\Sales\Repositories;
 */
interface SalesTeamRepo extends RepositoryInterface {


	/**
	 * Get the virtual member list
	 *
	 * @param string $method Method to fetch records from database
	 *
	 * @return array|\Illuminate\Support\Collection
	 */
	public function getVirtualMembersList( $method = 'get' );

	/**
	 * Get list of saleTeam member
	 *
	 * @param string $method Method to be used while fetching data
	 *
	 * @return array|Collection  It Returns empty array for invalid userId, Collection of sales team for valid userId
	 */
	public function getList( $method = 'get' );

	/**
	 * Get list of sale team member
	 *
	 * @param  int $userId Admin user id
	 *
	 * @return array|Collection  It Returns empty array for invalid userId, Collection of sales team for valid userId
	 */
	public function getListByUserId( $userId );

	/**
	 * Return lists for reported to
	 *
	 * @return Collection Returns list of members for reported to drop-down
	 */
	public function getListForReportedTo();

	/**
	 * Return list of unassigned members
	 *
	 * @return Collection Returns detail of unassigned member
	 */
	public function getUnassignedMember();

	/**
	 * Function for searching sales team member
	 *
	 * @param bool $paginate   Output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo     Number of the page
	 * @param int  $pageLength Length of the page
	 *
	 * @return LengthAwarePaginator|\Illuminate\Support\Collection Returns Collection or LengthAwarePaginate
	 * instance containing member list with applied criteria
	 */
	public function search( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );

	/**
	 * Get member detail
	 *
	 * @param int   $memberId Sales team member id
	 *
	 * @param array $where    array of where conditions
	 * @param array $columns  columns to fetch in result
	 *
	 * @return \Illuminate\Support\Collection Returns details of sales team member
	 */
	public function getMemberDetail( $memberId, array $where = [], $columns = [] );

	/**
	 * Get admin user's memberId
	 *
	 * @param int $adminId Admin user id
	 *
	 * @return int Returns member_id
	 */
	public function getMemberId( $adminId );

	/**
	 * Update Sales Team Member Detail
	 *
	 * @param array $memberData Array of date to be updated
	 * @param int   $memberId   MemberId to update it's record
	 *
	 * @return \stdClass|array|null  Returns updated member detail
	 */
	public function updateMember( $memberData, $memberId );

	/**
	 * Create sales team member
	 *
	 * @param array $memberData Supply data for sales member
	 *
	 * @return mixed Returns status of create operation
	 */
	public function createMember( $memberData );
}
