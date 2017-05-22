<?php

namespace App\Modules\Users\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface DeviceRegistrationRepo
 * @package namespace App\Modules\Users\Repositories\Contracts;
 */
interface DeviceRegistrationRepo extends RepositoryInterface {

	/**
	 * Get the total mobile users
	 *
	 * @return int
	 */
	public function getTotalUsers();
}
