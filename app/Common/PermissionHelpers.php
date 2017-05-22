<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 24/1/17
 * Time: 11:37 AM
 */

namespace App\Common;

use App;
use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;

/**
 * Class PermissionHelpers
 * @package App\Common
 */
class PermissionHelpers {

	/**
	 * Check if user has the permission to view the contact
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public static function canViewContact( $userId ) {
		$adminUser = App::make(AdminUsersRepo::class);

		return $adminUser->canViewContact($userId);
	}

	/**
	 * Check if user has the permission to export
	 *
	 * @param int $userId Id of the user
	 *
	 * @return int 1 if user has the permission, otherwise 0
	 */
	public static function canExport( $userId ) {
		$adminUser = App::make(AdminUsersRepo::class);

		return $adminUser->canExport($userId);
	}
}