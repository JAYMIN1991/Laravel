<?php

namespace App\Modules\Shared\Repositories\Contracts;

use App\Modules\Shared\Repositories\Course;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface CourseRepo
 *
 * @package namespace App\Modules\Content\Repositories;
 *
 * @see     Course
 */
interface CourseRepo extends RepositoryInterface {

	/**
	 *
	 */
	const USER_COURSE_ROLE_CREATOR = 1;

	/**
	 *
	 */
	const USER_COURSE_ROLE_TEACHER = 2;

	/**
	 *
	 */
	const USER_COURSE_ROLE_LEARNER = 3;

	/**
	 * Get Full course detail with user and location information
	 *
	 * @param int $courseId Id of the course
	 * @param bool $applyCoursePresenter apply course presenter on the result. Default is true
	 *
	 * @return  collection Returns course detail collection
	 */
	public function getFullCourseDetailWithUserAndLocationByCourseId( $courseId , $applyCoursePresenter = true);

	/**
	 * Get review history of particular course
	 *
	 * @param int $courseId Id of the course
	 *
	 * @return Collection Returns collection of review history
	 */
	public function getCourseReviewHistory( $courseId );

	/**
	 * Function for searching course review
	 *
	 * @param bool $paginate   Output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo     Number of the page
	 * @param int  $pageLength Length of the page
	 *
	 * @return LengthAwarePaginator|\Illuminate\Support\Collection Returns Collection or LengthAwarePaginate
	 * instance containing member list with applied criteria
	 */
	public function searchCoursesForReview( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );

	/**
	 * Search courses for which promotion is created
	 *
	 * @param bool $promotedOnly Show only the course who's promotion is done. default is true
	 * @param bool $paginate   Output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo     Number of the page
	 * @param int  $pageLength Length of the page
	 *
	 * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection Returns Collection or LengthAwarePaginate
	 * instance containing course list with applied criteria
	 */
	public function searchCoursesForPromotion($promotedOnly = true, $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT );

	/**
	 * Get course and course type details by course id
	 *
	 * @param int   $courseId   Id of the course
	 * @param array $attributes Array of attributes you want from database. Default: course_id, course_name, course_is_free, course_price, amount, course_promotion_rank, course_type
	 *
	 * @return array Course details
	 */
	public function getCourseAndCourseTypeByCourseId( $courseId, $attributes = [] );

	/**
	 * Get the list of courses
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getCourses( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the list of courses of an institute which are active, public, enrollment and course is still not ended.
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public  function getInstituteCoursesForPromotion($instituteId, $autoSuggest = false, array $attributes = []);

	/**
	 * Get the list of courses of an institute which are active, public, enrollment and course is still not ended.
	 * If applied priceType and PublicType filter then shows only applicable courses.
	 *
	 * @param       $instituteId Id of the institute
	 * @param int   $priceType   Price type can be free or paid. Value for paid is 1 and for free is 2.
	 * @param int   $publicType  Public type can be self paced or time-bound. Value for self-paced is 3 and for time bound is 2.
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getInstituteCoursesForPromotionByPriceAndPublicType( $instituteId, $priceType = null,
	                                                                     $publicType = null, $autoSuggest = false,
	                                                                     array $attributes = [] );

	/**
	 * Get the list of courses of an institute
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getInstituteCourses( $instituteId, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the list of courses of an institute from which content has been copied
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param bool  $showDeleted True will show the deleted courses
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed List of courses from which content has been copied
	 */
	public function getInstituteCoursesFromWhichContentCopied( $instituteId, $autoSuggest = false, $showDeleted = false,
	                                                           array $attributes = [] );

	/**
	 * Get the list of courses of an institute to which content has been copied
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param bool  $showDeleted True will show the deleted courses
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed List of courses to which content has been copied
	 */
	public function getInstituteCoursesToWhichContentCopied( $instituteId, $autoSuggest = false, $showDeleted = false,
	                                                         array $attributes = [] );

	/**
	 * Get the course details based on provided id
	 *
	 * @param int   $id         Id of the course
	 * @param array $attributes Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed Course details
	 */
	public function getCoursesById( $id, $attributes = [] );

	/**
	 * Get the free course/s details based on provided id/s
	 *
	 * @param int   $id      Id of the course
	 * @param array $columns Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed Course details
	 */
	public function getFreeCoursesById( $id, array $columns = [] );

	/**
	 * Get the number of courses user enrolled in
	 *
	 * @param int   $userId Id of the user
	 * @param array $role   Array of course roles
	 *
	 * @return int Count of enrollment
	 */
	public function getEnrollmentCount( $userId, array $role = null );

	/**
	 * Get the list of courses user is enrolled in
	 *
	 * @param int   $userId Id of user
	 *
	 * @param array $role   Array of roles
	 *
	 * @return mixed
	 */
	public function getUserEnrolledCourses( $userId, array $role = [
		self::USER_COURSE_ROLE_LEARNER,
		self::USER_COURSE_ROLE_TEACHER,
		self::USER_COURSE_ROLE_CREATOR
	] );

	/**
	 * Get course invitation code of teacher and learner of an institute
	 *
	 * @param int $instituteId Id of institute owner
	 *
	 * @return mixed Collection of invitation code of teacher and learner for an institute
	 */
	public function getCourseCodesOfInstitute( $instituteId );

	/**
	 * Get course invitation code of teacher and learner of a course
	 *
	 * @param int $courseId Id of course
	 *
	 * @return mixed Collection of invitation code of teacher and learner for an institute
	 */
	public function getCourseCodes( $courseId );

	/**
	 * Get the courses with learners count
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id,
	 *                           course_name, total_learners
	 *
	 * @return mixed
	 */
	public function getInstituteCoursesWithLearnerCount( $instituteId, $autoSuggest = false, array $attributes = [] );

	/**
	 * Update the course by course id
	 *
	 * @param int   $courseId provide course id
	 * @param array $data provide data to update
	 *
	 * @return int Return status of the update operation
	 */
	public function updateCourseByCourseId( $courseId, array $data);

	/**
	 * get Course Price using course id
	 * @param $courseId
	 *
	 * @return mixed
	 */
	public function getCoursePrice( $courseId);
}
