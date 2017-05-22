<?php

namespace App\Modules\Users\Repositories\Contracts;

use App\Modules\Users\Repositories\User;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface UserRepository
 * @package namespace App\Modules\Users\Repositories;
 * @see     User
 */
interface UserRepo extends RepositoryInterface {

	/**
	 * Return the list of unverified users.
	 *
	 * @param string|null $loginId Login Id of user
	 * @param int         $page    Number of page for pagination
	 *
	 * @return LengthAwarePaginator
	 */
	public function getUnverifiedUsers( $loginId = null, $page = 1 );

	/**
	 * Insert the user remarks from backoffice
	 * Data array must have the following data : `remark_text`,`remark_user_id`,`remark_user_inst_id`,
	 * `bkoff_user_id`,`remark_ip`,`remark_dt`,`device_type`
	 *
	 * @param array $data Array of data
	 *
	 * @return int Return the inserted id
	 * @throws \Exception
	 */
	public function insertUserRemarks( array $data );

	/**
	 * Get list of institute for after sales visit
	 *
	 * @param string $term           specify term to filter name
	 * @param bool   $autoSuggest    if true, will return column as id instead of user_id
	 * @param bool   $withEmailId    output school name with institute email id
	 * @param bool   $afterSalesOnly true means only show institute where after sales visit entry exists
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getInstituteListForAfterSalesVisit( $term = null, $autoSuggest = false, $withEmailId = false,
	                                                    $afterSalesOnly = false );

	/**
	 * Get the acquired institute detail by institute id
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $withEmail   If true, will return institute name with email id
	 * @param array $columns     List of columns to be retrieved
	 *
	 * @return array
	 */
	public function getAcquiredInstituteById( $instituteId, $withEmail = false, array $columns = [] );

	/**
	 * @param null|string  $term Search term
	 * @param bool  $autoSuggest  if true, will return column as id instead of user_id
	 * @param bool  $withEmailId If true, will return institute name with email id
	 * @param array $columns List of columns to be retrieved
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getNotAcquiredInstituteList( $term = null, $autoSuggest = false, $withEmailId = false,
	                                             $columns = [] );

	/**
	 * Get the institute name of the user
	 *
	 * @param int $userId Id of the user
	 *
	 * @return mixed Return the name of the the user in collection
	 */
	public function getInstituteNameFromUserCoursesTable( $userId );

	/**
	 * Get the list of users of provided institute
	 *
	 * @param bool $paginate True if calling as paginate, otherwise false
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
	 *
	 */
	public function getInstituteUsers( $paginate = false );
}
