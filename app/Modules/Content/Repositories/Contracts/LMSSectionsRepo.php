<?php

namespace App\Modules\Content\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface LMSSectionsRepo
 * @package namespace App\Modules\Content\Repositories\Contracts;
 */
interface LMSSectionsRepo extends RepositoryInterface
{
	/**
	 * Get section ids of provided course
	 *
	 * @param int $courseId Id of the course
	 *
	 * @return collection|null Returns collection of sectionIds or null
	 */
	public function getCourseSectionIds( $courseId );
}
