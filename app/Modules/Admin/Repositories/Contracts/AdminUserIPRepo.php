<?php

namespace App\Modules\Admin\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface AdminUserIPRepository
 * @package namespace App\Modules\Admin\Repositories;
 */
interface AdminUserIPRepo extends RepositoryInterface
{
	/**
	 * Get a list of allowed ip address for particular user
	 *
	 * @param int $userId Supply userId of current session
	 *
	 * @return array|bool Returns array of IPs or false
	 */
	public function getAllowedIP( $userId );
}
