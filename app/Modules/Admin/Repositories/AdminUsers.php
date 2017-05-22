<?php

namespace App\Modules\Admin\Repositories;

use App;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use App\Modules\Admin\Repositories\Criteria\AdminUserActiveCrit;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;
use Session;

/**
 * Class AdminUsersRepositoryEloquent
 * @package namespace App\Modules\Admin\Repositories;
 */
class AdminUsers extends BaseRepository implements AdminUsersRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'user_id';

	/**
	 * Function to get table name
	 *
	 * @return string Returns name of the table
	 */
	public function model() {
		return TABLE_ADMIN_USERS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(AdminUserActiveCrit::class);
	}

	/**
	 * Check if supplied user is valid admin user for Institute call visit
	 *
	 * @param int $userId Supply valid user_id
	 *
	 * @return bool Returns true if supplied user is Institute call visit admin
	 */
	public function isInstCallVisitAdmin( $userId ) {
		$isAdmin = false;
		if ( $this->isSiteAdmin($userId) ) {
			$isAdmin = true;
		} else {
			if ( defined('INST_CALL_VISIT_ADMIN_IDS') && INST_CALL_VISIT_ADMIN_IDS != '' ) {
				$adminIds = explode(',', INST_CALL_VISIT_ADMIN_IDS);

				if ( in_array($userId, $adminIds) ) {
					$isAdmin = true;
				}
			}
		}

		return $isAdmin;
	}

	/**
	 * Check if supplied user is site admin
	 *
	 * @param int $userId Supply valid user_id
	 *
	 * @return bool Returns true if supply user is BackOffice admin
	 */
	public function isSiteAdmin( $userId ) {
		return $userId == BACKOFFICE_ADMIN_ID;
	}

	/**
	 * Returns Single User Details
	 *
	 * @param string $user_id ID of user
	 *
	 * @param array  $columns Provides columns for user
	 *
	 * @return mixed Record of user
	 */
	public function getUser( $user_id, $columns = [] ) {
		$defaultColumns = [
			'user_id',
			'user_login',
			'user_firstname',
			'user_lastname',
			'user_mobile',
			'default_page_id'
		];

		if(!empty($columns)){
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		$results = $this->where($this->primaryKey, $user_id)->first($defaultColumns);
		return $this->parserResult($results);
	}

	/**
	 * Returns All Users
	 *
	 * @return Collection Returns all users
	 */
	public function getAllUsers() {
		$results = $this->select([
			'user_id',
			'user_login',
			'user_firstname',
			'user_lastname'
		])->get();

		return $this->parserResult($results);
	}

	/**
	 * Returns User Details to store in session
	 *
	 * @param int $userId Supply valid user_id
	 *
	 * @return array Returns array of user information
	 */
	public function getUserForSessionInitialise( $userId ) {
		$userInfo = [];

		$user = $this->getUser($userId, [
			'user_id',
		    'user_login',
		    'user_firstname',
		    'user_lastname',
		    'user_mobile',
		    'user_picture',
		    'default_page_id',
		    'user_is_active',
		    'restrict_by_ip',
		    'allow_export'
		]);

		if(!empty($user)){
			$userInfo = $user;
		}

		/* Fetch member details of logged in user */
		$userSalesTeamInfo = App::make(SalesTeamRepo::class)->getMemberDetail(null, [['admin_user_id', '=', $userId]]);

		if(!empty($userSalesTeamInfo)){
			$userInfo = array_merge($userInfo, $userSalesTeamInfo[0]);
		}

		return $userInfo;
	}

	/**
	 * Check if user has the permission to view the contact
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public function canViewContact( $userId ) {
		return $this->checkPermission('can_see_contact', $userId);
	}

	/**
	 * Check if user has the permission to export
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public function canExport( $userId ) {
		return $this->checkPermission('allow_export', $userId);
	}

	/**
	 * Check if the user has specified permission
	 *
	 * @param string $permissionName Name of the permission
	 * @param int    $userId         Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	private function checkPermission( $permissionName, $userId ) {

		// If given user id is super admin then skip checking in database
		if ( $userId == BACKOFFICE_ADMIN_ID ) {
			return 1;
		}

		// Check if given user id has the specified permission
		$permission = $this->select(TABLE_ADMIN_USERS . "." . $permissionName)
		                   ->where(TABLE_ADMIN_USERS . '.user_id', '=', $userId)
		                   ->first();

		return $permission[$permissionName];
	}

	/**
	 * Change password of current login user
	 * @param string $pwd New Password
	 * @return bool
	 */
	public function changePassword($pwd) {

		// Prepare data for update
		$passwordData  = ['user_password_v1' => password_hash($pwd, PASSWORD_BCRYPT)];

		// Update password
		$changePasswordStatus = $this->where('user_id', Session::get('user_id'))->update($passwordData);

		return ($changePasswordStatus ? true : false );
	}
}
