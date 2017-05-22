<?php

namespace App\Modules\Content\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface LMSContentsRepo
 * @package namespace App\Modules\Content\Repositories\Contracts;
 */
interface LMSContentsRepo extends RepositoryInterface
{
	/**
	 * Get Course Content by id of the course
	 *
	 * @param int  $courseId                    Id of the course
	 * @param bool $applyCourseContentPresenter Apply course content presenter on the result. Default is true
	 *
	 * @return collection|bool Returns collection of course contents, false in case of empty
	 */
	public function getCourseContentByCourseId( $courseId, $applyCourseContentPresenter = true );
}
