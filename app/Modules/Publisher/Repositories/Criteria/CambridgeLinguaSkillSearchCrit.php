<?php

namespace App\Modules\Publisher\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class CambridgeLinguaSkillSearchCritCrit
 * @package namespace App\Modules\Publisher\Repositories\Criteria;
 * @see     CambridgeLinguaSkillSearchCrit
 */
class CambridgeLinguaSkillSearchCrit extends AbstractCriteria {

	protected $request;

	/**
	 * CambridgeLinguaSkillSearchCrit constructor.
	 *
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		$instituteName = GeneralHelpers::clearParam($this->request->input('institute_name'), PARAM_RAW_TRIMMED);
		$instituteType = GeneralHelpers::clearParam($this->request->input('institute_type'), PARAM_RAW_TRIMMED);
		$stateId = GeneralHelpers::clearParam($this->request->input('state'), PARAM_RAW_TRIMMED);
		$examStartDate = GeneralHelpers::clearParam($this->request->input('exam_start_date'), PARAM_RAW_TRIMMED);
		$examEndDate = GeneralHelpers::clearParam($this->request->input('exam_end_date'), PARAM_RAW_TRIMMED);
		$city = GeneralHelpers::clearParam($this->request->input('city'), PARAM_RAW_TRIMMED);
		$noOfCandidateRangeId = GeneralHelpers::clearParam($this->request->input('no_of_candidate'), PARAM_RAW_TRIMMED);
		$registrationStartDate = GeneralHelpers::clearParam($this->request->input('registration_start_date'), PARAM_RAW_TRIMMED);
		$registrationEndDate = GeneralHelpers::clearParam($this->request->input('registration_end_date'), PARAM_RAW_TRIMMED);

		if ( $this->request->has('institute_name') ) {
			$model->where(TABLE_LEARN_LINGUASKILL_REG . '.inst_name', 'like', '%' . $instituteName . '%');
		}

		if ( $this->request->has('institute_type') ) {
			$model->where(TABLE_LEARN_LINGUASKILL_INST_TYPES . '.id', '=', $instituteType);
		}

		if ( $this->request->has('state') ) {
			$model->where(TABLE_STATES . '.state_id', '=', $stateId);
		}

		if ( $this->request->has('city') ) {
			$model->where(TABLE_LEARN_LINGUASKILL_REG . '.contact_city', '=', $city);
		}

		if ( $this->request->has('no_of_candidate') ) {
			$model->where(TABLE_LEARN_LINGUASKILL_CAND_RANGE . '.id', '=', $noOfCandidateRangeId);
		}

		if ( $this->request->has('exam_start_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_EXAM_DATES . '.exam_dt', [
				date('Y-m-d', strtotime($examStartDate)),
				'0000-00-00'
			]);
		}
		if ( $this->request->has('exam_end_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_EXAM_DATES . '.exam_dt', [
				'0000-00-00',
				date('Y-m-d', strtotime($examEndDate))
			]);
		}

		if ( $this->request->has('exam_start_date') && $this->request->has('exam_end_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_EXAM_DATES . '.exam_dt', [
				date('Y-m-d', strtotime($examStartDate)),
				date('Y-m-d', strtotime($examEndDate))
			]);
		}

		if ( $this->request->has('registration_start_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_REG . '.reg_date', [
				strtotime($registrationStartDate),
				strtotime('0000-00-00')
			]);
		}

		if ( $this->request->has('registration_end_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_REG . '.reg_date', [
				strtotime('0000-00-00'),
				strtotime($registrationEndDate)
			]);
		}

		if ( $this->request->has('registration_start_date') && $this->request->has('registration_end_date') ) {
			$model->whereBetween(TABLE_LEARN_LINGUASKILL_REG . '.reg_date', [
				strtotime($registrationStartDate),
				strtotime($registrationEndDate)
			]);
		}

		return $model;
	}
}
