<?php

namespace App\Modules\Shared\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface BackOfficeJobResultsRepo
 * @package namespace App\Modules\Shared\Repositories\Contracts;
 */
interface BackOfficeJobResultsRepo extends RepositoryInterface {

	/**
	 * Insert the job to database
	 *
	 * @param string $jobName        Name of the job
	 * @param string $jobDescription Description of of job
	 * @param int    $userId         User id of the logged in user
	 * @param array  $parameters     Extra parameter of the job
	 *
	 * @return int
	 */
	public function initializeJob( $jobName, $jobDescription, $userId, $parameters = [] );

	/**
	 * Get the details of job
	 *
	 * @param int   $jobId   Id of the job
	 * @param array $columns Extra columns you want from database. Default columns : `job_id`, `job_name`
	 *
	 * @return mixed
	 */
	public function getJob( $jobId, array $columns = [] );

	/**
	 * Update the job
	 *
	 * @param array $data  data to be update
	 * @param int   $jobId Id of the job
	 *
	 * @return mixed
	 */
	public function updateJob( array $data, $jobId );
}
