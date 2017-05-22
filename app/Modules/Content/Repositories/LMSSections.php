<?php

namespace App\Modules\Content\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Content\Repositories\Contracts\LMSSectionsRepo;

/**
 * Class LMSSections
 * @package namespace App\Modules\Content\Repositories;
 */
class LMSSections extends BaseRepository implements LMSSectionsRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'section_id';

	/**
	 * Provide table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_LMS_SECTIONS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get section ids of provided course
	 *
	 * @param int $courseId Id of the course
	 *
	 * @return array Returns array of sectionIds or empty array
	 */
	public function getCourseSectionIds( $courseId ) {

		$this->distinct()->select([
			TABLE_LMS_SECTIONS . '.section_id',
			TABLE_LMS_SECTIONS . '.section_srno',
			TABLE_LMS_CONTENTS . '.content_srno'
		]);

		$this->join(TABLE_LMS_CONTENTS, TABLE_LMS_CONTENTS . '.section_id', '=', TABLE_LMS_SECTIONS . '.section_id')
		     ->join(TABLE_LMS_ATTACHMENTS,TABLE_LMS_ATTACHMENTS.'.content_id', '=', TABLE_LMS_CONTENTS.'.content_id');

		$this->where(TABLE_LMS_SECTIONS.'.course_id', '=',$courseId)
		     ->where(TABLE_LMS_SECTIONS.'.section_deleted', '=',0)
		     ->where(TABLE_LMS_SECTIONS.'.section_visible','=',1)
		     ->where(TABLE_LMS_CONTENTS.'.content_deleted', '=',0)
		     ->where(TABLE_LMS_CONTENTS. '.content_visible', '=', 1);

		$this->orderBy(TABLE_LMS_SECTIONS . '.section_srno')
			 ->orderBy(TABLE_LMS_CONTENTS . '.content_srno');

		$result = $this->get()->unique('section_id')->pluck('section_id')->all();

		return $this->parserResult($result);
	}
}
