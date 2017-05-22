<?php

namespace App\Modules\Sales\Repositories;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Sales\Repositories\Criteria\IsVirtualSalesMemberCrit;
use App\Modules\Sales\Repositories\Criteria\SalesTeamSearchCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Flinnt\Repository\Traits\CacheableRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Implementation for SalesTeamRepo
 * Class SalesTeam
 * @package namespace App\Modules\Sales\Repositories;
 */
class SalesTeam extends BaseRepository implements SalesTeamRepo {

	use CacheableRepository;

	/**
	 * Primary Key
	 * @var string
	 */
	protected $primaryKey = 'member_id';

	/**
	 * Function to get table name
	 *
	 * @return string Returns actual name of table
	 */
	public function model() {
		return TABLE_BACKOFFICE_SALES_TEAM;
	}

	/**
	 * Method for applying default criteria
	 */
	protected function boot() {
	}

	/**
	 * Get the virtual member list
	 *
	 * @param string $method Method to fetch records from database
	 *
	 * @return array|\Illuminate\Support\Collection
	 */
	public function getVirtualMembersList( $method = 'get' ) {
		$this->pushCriteria(IsVirtualSalesMemberCrit::class);

		return $this->getList($method);
	}

	/**
	 * Get list of saleTeam member
	 *
	 * @param string $method Method to be used while fetching data
	 *
	 * @return array|\Illuminate\Support\Collection It Returns empty array for invalid userId, Collection of sales team for valid userId
	 */
	public function getList( $method = 'get' ) {
		$result = null;

		$this->orderBy('display_order');

		switch ( $method ) {
			case 'pluck';
				$result = $this->pluck(DB::raw("CONCAT( CONCAT_WS(' ', first_name, last_name ),' (', city_name ,')') as user_name"), 'member_id');
				break;
			default:
				$result = $this->get([
					DB::raw("CONCAT( CONCAT_WS(' ', first_name, last_name ),' (', city_name ,')') as user_name"),
					'member_id'
				]);
		}

		return $this->parserResult($result);
	}

	/**
	 * Get list of sale team member
	 *
	 * @param  int $userId Admin user id
	 *
	 * @return array|Collection  It Returns empty array for invalid userId, Collection of sales team for valid userId
	 */
	public function getListByUserId( $userId ) {

		if ( $userId ) {
			$userId = (int) $userId;

			$this->select([
				'admin_user_id',
				DB::raw("CONCAT(
                                CONCAT_WS(' ', first_name, last_name ), 
                                ' (', 
                                city_name , 
                                ')' 
                                ) as user_name")
			]);

			/* @var AdminUsersRepo $adminUserRepo */
			$adminUserRepo = App::make(AdminUsersRepo::class);

			if ( $adminUserRepo->isSiteAdmin($userId) || $adminUserRepo->isInstCallVisitAdmin($userId) ) {

				$this->where('admin_user_id', '<>', '')->whereNotNull('admin_user_id');

			} else {
				$this->where('member_id', $userId)->orWhere('parent_member_id', $userId);
			}
			$result = $this->orderBy('display_order')->pluck('user_name', 'admin_user_id');

			return $this->parserResult($result);
		}

		return [];
	}

	/**
	 * Return lists for reported to
	 *
	 * @return Collection Returns list of members for reported to drop-down
	 */
	public function getListForReportedTo() {
		$result = $this->pluck(DB::raw("CONCAT(
											first_name,
											' ', 
											last_name,
											' ', 
											IF( is_left = 1, '- left' , '' ) ) as name"), 'member_id');

		return $this->parserResult($result);
	}

	/**
	 * Return list of unassigned members
	 *
	 * @return Collection Returns detail of unassigned member
	 */
	public function getUnassignedMember() {
		$result = $this->where('admin_user_id', null)->get();

		return $this->parserResult($result);
	}

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
	public function search( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT ) {
		$this->pushCriteria(App::make(SalesTeamSearchCrit::class));

		$this->select([
			'member_id',
			'first_name',
			'last_name',
			'city_name',
			'is_left',
			'parent_member_id',
			DB::raw("(
									SELECT 
											CONCAT(first_name,' ',last_name) as reported_to 
									FROM 
											" . $this->model() . " r 
									WHERE 
											r.member_id = " . $this->model() . ".parent_member_id 
									LIMIT   0,1
								  ) as reported_to"),
		]);

		$results = $paginate ? $this->paginate(PAGINATION_RECORD_COUNT, $pageNo) : $this->get();

		return $this->parserResult($results);
	}

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
	public function getMemberDetail( $memberId, array $where = [], $columns = [] ) {
		$defaultColumns = [
			'member_id',
			'first_name',
			'last_name',
			'city_name',
			'city_name_abbr',
			'is_left',
			'display_order',
			'admin_user_id',
			'parent_member_id',
			'virtual_member'
		];

		/* Merge the columns if provided in parameter */
		if ( ! empty($defaultColumns) ) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		/* Fetch member or multiple members with custom where clause */
		if ( ! GeneralHelpers::isNull($memberId) ) {
			$results = $this->where('member_id', $memberId)->first();
		} else {
			$this->applyConditions($where);
			$results = $this->get($defaultColumns);
		}

		return $this->parserResult($results);
	}

	/**
	 * Get admin user's memberId
	 *
	 * @param int $adminId Admin user id
	 *
	 * @return int Returns member_id
	 */
	public function getMemberId( $adminId ) {
		$result = $this->where('admin_user_id', $adminId)->value('member_id');

		return $this->parserResult($result);
	}

	/**
	 * Update Sales Team Member Detail
	 *
	 * @param array $memberData Array of date to be updated
	 * @param int   $memberId   MemberId to update it's record
	 *
	 * @return \stdClass|array|null  Returns updated member detail
	 */
	public function updateMember( $memberData, $memberId ) {

		return $this->updateById($memberData, $memberId);
	}

	/**
	 * Create sales team member
	 *
	 * @param array $memberData Supply data for sales member
	 *
	 * @return mixed Returns status of create operation
	 */
	public function createMember( $memberData ) {

		return $this->create($memberData);
	}
}
