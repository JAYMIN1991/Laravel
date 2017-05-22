<?php

namespace App\Modules\Publisher\Repositories;

use App\Modules\Publisher\Repositories\Criteria\CambridgeLinguaSkillSearchCrit;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Publisher\Repositories\Contracts\CambridgeLinguaSkillSearchRepo;


/**
 * Class CambridgeLinguaSkillSearchRepo
 * @package namespace App\Modules\Publisher\Repositories;
 * @see     CambridgeLinguaSkillSearch
 */
class CambridgeLinguaSkillSearch extends BaseRepository implements CambridgeLinguaSkillSearchRepo {

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
		return TABLE_LEARN_LINGUASKILL_REG;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * @return mixed
	 */
	public function getInstituteTypeListLinguaSkill() {
		$result = $this->from(TABLE_LEARN_LINGUASKILL_INST_TYPES)
		               ->where('is_active', '=', '1')
		               ->pluck('type_text', 'id');

		return $this->parserResult($result);
	}

	/**
	 * Get city list
	 * @return mixed
	 */
	public function getCityListLinguaSkillSearch() {
		$result = $this->distinct()
		               ->select('contact_city')
		               ->from(TABLE_LEARN_LINGUASKILL_REG)
		               ->pluck('contact_city', 'contact_city');

		return $this->parserResult($result);
	}

	// Get range collection for lingua skill
	public function getRangeLinguaSkillCandidate() {
		$result = $this->from(TABLE_LEARN_LINGUASKILL_CAND_RANGE)->pluck('range_text', 'id');

		return $this->parserResult($result);
	}

	// get search result for lingua search
	public function getLinguaSkillSearchResult( $pagination = false ) {
		$this->pushCriteria(app(CambridgeLinguaSkillSearchCrit::class));

		$this->select([
			TABLE_LEARN_LINGUASKILL_REG . '.inst_name',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_address',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_email',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_city',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_phone',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_person',
			TABLE_LEARN_LINGUASKILL_REG . '.contact_person_phone',
			TABLE_LEARN_LINGUASKILL_REG . '.designation',
			TABLE_LEARN_LINGUASKILL_REG . '.reg_date',
			TABLE_LEARN_LINGUASKILL_INST_TYPES . '.type_text',
			TABLE_STATES . '.state_name',
			TABLE_LEARN_LINGUASKILL_CAND_RANGE . '.range_text',
			TABLE_LEARN_LINGUASKILL_EXAM_DATES . '.exam_date'
		])
		     ->from(TABLE_LEARN_LINGUASKILL_REG)
		     ->leftJoin(TABLE_LEARN_LINGUASKILL_INST_TYPES, TABLE_LEARN_LINGUASKILL_INST_TYPES . '.id', '=', TABLE_LEARN_LINGUASKILL_REG . '.inst_type_id')
		     ->leftJoin(TABLE_STATES, TABLE_STATES . '.state_id', '=', TABLE_LEARN_LINGUASKILL_REG . '.contact_state_id')
		     ->leftJoin(TABLE_LEARN_LINGUASKILL_CAND_RANGE, TABLE_LEARN_LINGUASKILL_CAND_RANGE . '.id', '=', TABLE_LEARN_LINGUASKILL_REG . '.cand_range_id')
		     ->leftJoin(TABLE_LEARN_LINGUASKILL_EXAM_DATES, TABLE_LEARN_LINGUASKILL_EXAM_DATES . '.id', '=', TABLE_LEARN_LINGUASKILL_REG . '.exam_date_id')
		     ->orderBy(TABLE_LEARN_LINGUASKILL_REG . '.id', 'DESC');

		// keep this code
		//dd($this->toSql());

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}
}
