<?php

namespace App\Modules\Publisher\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CambridgeSubmissionsRepo
 * @package namespace App\Modules\Publisher\Repositories\Contracts;
 */
interface CambridgeSubmissionsRepo extends RepositoryInterface {

	/**
	 * @param bool $pagination
	 *
	 * @return mixed
	 */
	public function getCambridgeRegistrationSubmissionsResult( $pagination = false );

	/**
	 * @param $submissionId
	 *
	 * @return mixed
	 */
	public function checkSubmissionDataBySubmissionId( $submissionId );

	/**
	 * @param $registrationId
	 *
	 * @return mixed
	 */
	public function getSubmissionViewData( $registrationId );

	/**
	 * @param $registrationId
	 *
	 * @return mixed
	 */
	public function downloadRegistrationZip( $registrationId );

	/**
	 *
	 * @param $submissionId
	 *
	 * @return mixed
	 */
	public function downloadSubmissionZip( $submissionId );
}
