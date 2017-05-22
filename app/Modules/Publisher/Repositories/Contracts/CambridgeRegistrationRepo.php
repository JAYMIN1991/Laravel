<?php

namespace App\Modules\Publisher\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CambridgeRegistrationRepo
 * @package namespace App\Modules\Publisher\Repositories\Contracts;
 */
interface CambridgeRegistrationRepo extends RepositoryInterface
{

	/**
	 * @param bool $pagination
	 *
	 * @return mixed
	 */
	public function getCambridgeRegistrationSearch( $pagination = false);

	/**
	 * cambridge registration name list
	 * @return mixed
	 */
	public function getCambridgeRegistrationNameList();

	/**
	 * @param bool $pagination
	 * @param null $regId
	 *
	 * @return mixed
	 */
	public function getCambridgeRegistrationRecord( $pagination = false, $regId = null);
}
