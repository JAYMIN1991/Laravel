<?php

namespace App\Modules\Admin\Repositories\Contracts;

use App\Modules\Admin\Repositories\AdminUsers;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface AdminUsersRepository
 * @package namespace App\Modules\Admin\Repositories;
 * @see AdminUsers
 */
interface AdminUsersRepo extends RepositoryInterface {

	/**
	 * Returns User Details to store in session
	 * @param $userId
	 *
	 * @return array
	 */
	public function getUserForSessionInitialise($userId);

	/**
	 * Check if supplied user is site admin
	 *
	 * @param int $userId Supply valid user id
	 *
	 * @return bool Returns true if supply user is BackOffice admin
	 */
	public function isSiteAdmin($userId);

	/**
	 * Check if supplied user is valid admin user for Institute call visit
	 *
	 * @param int $userId Supply valid user id
	 *
	 * @return bool Returns true if supplied user is Institute call visit admin
	 */
	public function isInstCallVisitAdmin($userId);

	/**
	 * Returns All Users
	 *
	 * @return Collection Returns all users
	 */
	public function getAllUsers();

	/**
	 * Returns Single User Details
	 *
	 * @param string $user_id ID of user
	 *
	 * @param array  $columns Provides columns for user
	 *
	 * @return mixed Record of user
	 */
	public function getUser( $user_id, $columns = [] );

	/**
	 * Check if user has the permission to view the contact
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public function canViewContact( $userId );

	/**
	 * Check if user has the permission to export
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public function canExport( $userId );

}
