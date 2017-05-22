<?php

namespace App\Modules\Content\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Content\Repositories\Contracts\CourseCategoriesRepo;
use Illuminate\Support\Collection;

/**
 * Class CourseCategories
 * @package namespace App\Modules\Content\Repositories;
 */
class CourseCategories extends BaseRepository implements CourseCategoriesRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'category_id';

	/**
	 * Provide table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_COURSE_CATEGORIES;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get course categories by providing course id
	 *
	 * @param int $courseId Id of the course
	 *
	 * @return  Collection  Return collection of categories
	 */
	public function getCourseCategoriesByCourseId( $courseId ) {
		$result = $this->select(['category_name'])
		               ->leftJoin(TABLE_COURSE_CATEGORY_APPLICABLE, TABLE_COURSE_CATEGORY_APPLICABLE . '.category_id', '=', TABLE_COURSE_CATEGORIES . '.category_id')
		               ->where(TABLE_COURSE_CATEGORY_APPLICABLE . '.course_id', '=', $courseId)
		               ->where(TABLE_COURSE_CATEGORIES . '.category_active', 1)
		               ->orderBy(TABLE_COURSE_CATEGORIES . '.category_srno')
			           ->get();

		return $this->parserResult($result);
	}
}
