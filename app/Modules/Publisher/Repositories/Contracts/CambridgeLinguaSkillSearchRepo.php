<?php

namespace App\Modules\Publisher\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CambridgeLinguaSkillSearchRepoRepo
 * @package namespace App\Modules\Publisher\Repositories\Contracts;
 * @see     CambridgeLinguaSkillSearchRepo
 */
interface CambridgeLinguaSkillSearchRepo extends RepositoryInterface {

	//get institute type list for lingua skill
	public function getInstituteTypeListLinguaSkill();

	// get City list
	public function getCityListLinguaSkillSearch();

	// get no. of candidate range
	public function getRangeLinguaSkillCandidate();

	// get lingua skill search result
	public function getLinguaSkillSearchResult( $pagination = false );
}
