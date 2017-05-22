<?php

namespace App\Modules\Users\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Users\Repositories\Contracts\DeviceRegistrationRepo;

/**
 * Class DeviceRegistration
 * @package namespace App\Modules\Users\Repositories;
 */
class DeviceRegistration extends BaseRepository implements DeviceRegistrationRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'id';


	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_DEVICE_REGISTRATIONS;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get the total mobile users
	 *
	 * @return int
	 */
	public function getTotalUsers() {

		return $this->distinct()->count('user_id');
	}
}
