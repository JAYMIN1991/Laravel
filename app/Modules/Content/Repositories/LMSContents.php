<?php

namespace App\Modules\Content\Repositories;

use App;
use App\Modules\Content\Presenters\CourseContentPresenter;
use App\Modules\Content\Repositories\Contracts as Repositories;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;

/**
 * Class LMSContents
 * @package namespace App\Modules\Content\Repositories;
 */
class LMSContents extends BaseRepository implements Repositories\LMSContentsRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'content_id';

	/**
	 * provide table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_LMS_CONTENTS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get Course Content by id of the course
	 *
	 * @param int  $courseId                    Id of the course
	 * @param bool $applyCourseContentPresenter Apply course content presenter on the result. Default is true
	 *
	 * @return collection|bool Returns collection of course contents, false in case of empty
	 */
	public function getCourseContentByCourseId( $courseId, $applyCourseContentPresenter = true ) {

		$sectionIds = App::make(Repositories\LMSSectionsRepo::class)->getCourseSectionIds($courseId);

		/* Return empty collection if no sections for given courseId */
		if ( empty($sectionIds) ) {
			return new Collection();
		}

		/* Apply presenter if $applyCourseContentPresenter is set to true */
		if ( $applyCourseContentPresenter ) {
			$this->setPresenter(CourseContentPresenter::class);
		}

		$this->select([
			TABLE_LMS_SECTIONS . '.section_id',
			TABLE_LMS_SECTIONS . '.section_title',
			TABLE_LMS_CONTENTS . '.section_id',
			TABLE_LMS_CONTENTS . '.content_id',
			TABLE_LMS_CONTENTS . '.content_type',
			TABLE_LMS_CONTENTS . '.content_title',
			TABLE_LMS_ATTACHMENTS . '.attach_id',
			TABLE_LMS_ATTACHMENTS . '.attach_file',
			TABLE_LMS_ATTACHMENTS . '.attach_preview',
			DB::raw("IF(" . TABLE_LMS_CONTENTS . ".content_copy_source > 0, (SELECT u.user_school_name
                                 FROM flt_users u, flt_courses crs
                                 WHERE u.user_id = crs.course_owner AND crs.course_id = " . TABLE_LMS_CONTENTS . ".course_id), '') `copied_from`")
		]);

		$this->join(TABLE_LMS_SECTIONS, TABLE_LMS_SECTIONS . '.section_id', '=', TABLE_LMS_CONTENTS . '.section_id')
		     ->join(TABLE_LMS_ATTACHMENTS, TABLE_LMS_ATTACHMENTS . '.content_id', '=', TABLE_LMS_CONTENTS . '.content_id');

		$this->where(TABLE_LMS_SECTIONS . '.course_id', '=', $courseId)
		     ->where(TABLE_LMS_SECTIONS . '.section_deleted', '=', 0)
		     ->where(TABLE_LMS_SECTIONS . '.section_visible', '=', 1)
		     ->where(TABLE_LMS_CONTENTS . '.content_deleted', '=', 0)
		     ->where(TABLE_LMS_CONTENTS . '.content_visible', '=', 1)
		     ->whereIn(TABLE_LMS_SECTIONS . '.section_id', $sectionIds);

		$result = $this->get();

		return $this->parserResult($result);
	}
}
