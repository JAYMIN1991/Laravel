<?php
namespace App\Modules\Admin\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Admin\Repositories\Contracts\AdminUserIPRepo;

/**
 * Class AdminUserIPRepositoryEloquent
 * @package namespace App\Modules\Admin\Repositories;
 */
class AdminUserIP extends BaseRepository implements AdminUserIPRepo
{
   /**
   	 * Primary Key
   	 * @var String
   	 */
   	protected $primaryKey = 'ip_restriction_id';

    /**
     * Method to get table name
     *
     * @return string Returns name of the table
     */
    public function model()
    {
        return TABLE_ADMIN_USERS_IP;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }

	/**
	 * Get a list of allowed ip address for particular user
	 *
	 * @param int $userId Supply userId of current session
	 *
	 * @return array|bool Returns array of IPs or false
	 */
	public function getAllowedIP( $userId )
	{
		$AllowedIP = $this->where('user_id', $userId)->get(['ip_range'])->toArray();
		if ( count($AllowedIP) > 0 ) {
			return array_flatten($AllowedIP);
		}

		return false;
	}
}
