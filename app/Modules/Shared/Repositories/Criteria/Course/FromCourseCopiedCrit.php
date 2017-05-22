<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;

/**
 * Class FromCourseCopiedCrit
 * @package namespace App\Modules\Shared\Repositories\Criteria\Course;
 */
class FromCourseCopiedCrit extends AbstractCriteria
{
	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
    public function apply($model, RepositoryInterface $repository)
    {
	    $model->whereIn($this->getAttributeName(TABLE_COURSES, 'course_id'), function ( $query ) {
		    /** @var BaseRepository $query */
		    $query->distinct()
		          ->select($this->getAttributeName(TABLE_LMS_COPY_CONTENT, 'copy_from_course_id'))
		          ->from(TABLE_LMS_COPY_CONTENT);
	    });

        return $model;
    }
}
