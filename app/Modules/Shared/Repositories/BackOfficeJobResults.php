<?php

namespace App\Modules\Shared\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Shared\Repositories\Contracts\BackOfficeJobResultsRepo;
use Helper;

/**
 * Class BackOfficeJobResults
 * @package namespace App\Modules\Shared\Repositories;
 */
class BackOfficeJobResults extends BaseRepository implements BackOfficeJobResultsRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'job_id';


	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_BACKOFFICE_JOB_RESULTS;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

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
	public function initializeJob( $jobName, $jobDescription, $userId, $parameters = [] ) {
		$defaultData = [];
		$defaultData["job_name"] = $jobName;
		$defaultData["job_log"] = $jobDescription;
		$defaultData["job_user_id"] = $userId;
		$defaultData['job_dt'] = Helper::datetimeToTimestamp();
		$defaultData['job_ip'] = Helper::getIPAddress();
		$defaultData['job_device_type'] = 'BACKOFFICE';
		$defaultData['job_status'] = BACKOFFICE_JOB_STATUS_INIT;
		$defaultData['job_parameters'] = (! empty($parameters) ? serialize($parameters) : '');

		return $this->insertGetId($defaultData);
	}

	/**
	 * Get the details of job
	 *
	 * @param int   $jobId   Id of the job
	 * @param array $columns Extra columns you want from database. Default columns : `job_id`, `job_name`
	 *
	 * @return mixed
	 */
	public function getJob( $jobId, array $columns = [] ) {
		$cols = ['job_id', 'job_name'];

		if ( ! empty($columns) ) {
			$cols = $this->mergeColumns($cols, $columns);
		}

		$results = $this->find($jobId, $cols);

		return $this->parserResult($results);
	}

	/**
	 * Update the job
	 *
	 * @param array $data  data to be update
	 * @param int   $jobId Id of the job
	 *
	 * @return mixed
	 */
	public function updateJob( array $data, $jobId ) {
		$result = $this->updateById($data, $jobId);

		if ($result) {
			return true;
		}

		return false;
	}

	/**
	 * Merge the columns
	 *
	 * @param array $defaultColumns Array of default columns
	 * @param array $extraColumns   Array of extra columns
	 *
	 * @return array
	 */
	private function mergeColumns( array &$defaultColumns, array &$extraColumns ) {
		return array_merge($defaultColumns, $extraColumns);
	}
}
