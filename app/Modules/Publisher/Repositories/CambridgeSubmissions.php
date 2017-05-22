<?php

namespace App\Modules\Publisher\Repositories;

use App\Modules\Publisher\Repositories\Criteria\CambridgeSubmissionsCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Publisher\Repositories\Contracts\CambridgeSubmissionsRepo;

/**
 * Class CambridgeSubmissions
 * @package namespace App\Modules\Publisher\Repositories;
 */
class CambridgeSubmissions extends BaseRepository implements CambridgeSubmissionsRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'submission_id';

	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_CELAT_SUBMISSIONS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * @param bool $pagination
	 * @return send Submission record set
	 */
	public function getCambridgeRegistrationSubmissionsResult( $pagination = false ) {
		// Push criteria to search condition
		$this->pushCriteria(app(CambridgeSubmissionsCrit::class));
		// Select column name
		$this->select([
			TABLE_CELAT_SUBMISSIONS . '.submission_id',
			TABLE_CELAT_SUBMISSIONS . '.sub_exam_category',
			TABLE_CELAT_SUBMISSIONS . '.sub_activity_type',
			TABLE_CELAT_SUBMISSIONS . '.sub_place',
			DB::raw('DATE(FROM_UNIXTIME(' . TABLE_CELAT_SUBMISSIONS . '.sub_date)) as sub_date'),
			TABLE_CELAT_REGISTRATIONS . '.reg_institute',
			TABLE_CELAT_REGISTRATIONS . '.reg_name',
			TABLE_CELAT_REGISTRATIONS . '.reg_mobile'
		])
		     ->leftJoin(TABLE_CELAT_REGISTRATIONS, TABLE_CELAT_REGISTRATIONS . '.reg_id', '=', TABLE_CELAT_SUBMISSIONS . '.sub_reg_id')
		     ->orderBy(TABLE_CELAT_SUBMISSIONS . '.submission_id', 'ASC');

		// Check condition for pagination or not
		$result = ($pagination) ? $this->paginate(PAGINATION_RECORD_COUNT) : $this->get();

		return $this->parserResult($result);
	}

	/**
	 * Get submission Data using submission id
	 * @param $submissionId
	 *
	 * @return mixed
	 */
	public function checkSubmissionDataBySubmissionId( $submissionId ) {
		$result = $this->where('submission_id', '=', $submissionId)->pluck('sub_reg_id');

		return $this->parserResult($result);
	}

	/**
	 * Get submission details using registration id
	 * @param $registrationId
	 *
	 * @return mixed
	 */
	public function getSubmissionViewData( $registrationId ) {
		$this->select([
			TABLE_CELAT_SUBMISSIONS . '.submission_id',
			TABLE_CELAT_SUBMISSIONS . '.sub_exam_category',
			TABLE_CELAT_SUBMISSIONS . '.sub_activity_type',
			TABLE_CELAT_SUBMISSIONS . '.sub_description',
			TABLE_CELAT_SUBMISSIONS . '.sub_time_required',
			TABLE_CELAT_SUBMISSIONS . '.sub_aims',
			TABLE_CELAT_SUBMISSIONS . '.sub_material_required',
			TABLE_CELAT_SUBMISSIONS . '.sub_procedure',
			TABLE_CELAT_SUBMISSIONS . '.sub_place',
			TABLE_CELAT_SUBMISSIONS . '.sub_date',
			DB::raw("(GROUP_CONCAT(CONCAT_WS('*COL*', " . TABLE_CELAT_SUBMISSION_FILES . ".file_name, " . TABLE_CELAT_SUBMISSION_FILES . ".disk_name) ORDER BY " . TABLE_CELAT_SUBMISSION_FILES . ".sub_file_id SEPARATOR '*ROW*')) attachments ")
		])
		     ->leftJoin(TABLE_CELAT_SUBMISSION_FILES, TABLE_CELAT_SUBMISSION_FILES . '.submission_id', '=', TABLE_CELAT_SUBMISSIONS . '.submission_id')
		     ->where(TABLE_CELAT_SUBMISSIONS . '.sub_reg_id', '=', $registrationId)
		     ->groupBy(TABLE_CELAT_SUBMISSIONS . '.submission_id', TABLE_CELAT_SUBMISSIONS . '.sub_exam_category', TABLE_CELAT_SUBMISSIONS . '.sub_activity_type', TABLE_CELAT_SUBMISSIONS . '.sub_description', TABLE_CELAT_SUBMISSIONS . '.sub_time_required', TABLE_CELAT_SUBMISSIONS . '.sub_aims', TABLE_CELAT_SUBMISSIONS . '.sub_material_required', TABLE_CELAT_SUBMISSIONS . '.sub_procedure', TABLE_CELAT_SUBMISSIONS . '.sub_place', TABLE_CELAT_SUBMISSIONS . '.sub_date')
		     ->orderBy('sub_date', 'DESC');

		return $this->parserResult($this->get());
	}

	/**
	 * Get download registration zip details using registration id
	 * @param $registrationId
	 *
	 * @return mixed
	 */
	public function downloadRegistrationZip( $registrationId ) {
		$this->select([
			TABLE_CELAT_SUBMISSIONS . '.submission_id',
			TABLE_CELAT_SUBMISSIONS . '.sub_date',
			TABLE_CELAT_SUBMISSION_FILES . '.disk_name',
			TABLE_CELAT_SUBMISSION_FILES . '.file_name'
		])
		     ->leftJoin(TABLE_CELAT_SUBMISSION_FILES, TABLE_CELAT_SUBMISSION_FILES . '.submission_id', '=', TABLE_CELAT_SUBMISSIONS . '.submission_id')
		     ->where(TABLE_CELAT_SUBMISSIONS . '.sub_reg_id', '=', $registrationId)
		     ->orderBy(TABLE_CELAT_SUBMISSIONS . '.submission_id')
		     ->orderBy(TABLE_CELAT_SUBMISSION_FILES . '.sub_file_id');

		return $this->parserResult($this->get());
	}

	/**
	 * Get submission download details using submission Id
	 * @param $submissionId
	 *
	 * @return mixed
	 */
	public function downloadSubmissionZip( $submissionId ) {
		$this->select([
			TABLE_CELAT_SUBMISSIONS . '.submission_id',
			TABLE_CELAT_SUBMISSIONS . '.sub_date',
			TABLE_CELAT_SUBMISSION_FILES . '.disk_name',
			TABLE_CELAT_SUBMISSION_FILES . '.file_name'
		])
		     ->leftJoin(TABLE_CELAT_SUBMISSION_FILES, TABLE_CELAT_SUBMISSION_FILES . '.submission_id', '=', TABLE_CELAT_SUBMISSIONS . '.submission_id')
		     ->where(TABLE_CELAT_SUBMISSIONS . '.submission_id', '=', $submissionId)
		     ->orderBy(TABLE_CELAT_SUBMISSIONS . '.submission_id');

		return $this->parserResult($this->get());
	}
}
