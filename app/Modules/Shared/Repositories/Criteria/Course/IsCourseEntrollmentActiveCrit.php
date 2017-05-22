<?php

namespace App\Modules\Shared\Repositories\Criteria\Course;

use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Database\Query\Builder;

/**
 * Class CourseEntrollmentActiveCriteria
 * @package namespace App\Modules\Content\Criteria;
 */
class IsCourseEntrollmentActiveCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$timestamp = Helper::datetimeToTimestamp();
		$model = $model->where(function ( $query ) use ( $timestamp ) {
			/** @var  Builder $query */
			$query->where($this->getAttributeName(TABLE_COURSES, 'course_enrollment_end_date'), '=', 0)
			      ->orWhere($this->getAttributeName(TABLE_COURSES, 'course_enrollment_end_date'), '>=', $timestamp);
		})->where(function ( $query ) use ( $timestamp ) {
			/** @var  Builder $query */
			$query->where($this->getAttributeName(TABLE_COURSES, 'course_end_date'), '=', 0)
			      ->orWhere($this->getAttributeName(TABLE_COURSES, 'course_end_date'), '>=', $timestamp);
		});

		return $model;
	}
}
