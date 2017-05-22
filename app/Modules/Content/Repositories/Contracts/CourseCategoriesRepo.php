<?php

namespace App\Modules\Content\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface CourseCategoriesRepo
 * @package namespace App\Modules\Content\Repositories\Contracts;
 */
interface CourseCategoriesRepo extends RepositoryInterface
{

	/**
	 * Get course categories by providing course id
	 *
	 * @param $courseId
	 * @return  Collection  Return collection of categories
	 */
	public function getCourseCategoriesByCourseId( $courseId );
}
