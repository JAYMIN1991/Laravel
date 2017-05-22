<?php

namespace App\Modules\Shared\Repositories;

use App;
use App\Modules\Content\Presenters\CoursePresenter;
use App\Modules\Course\Repositories\Criteria\SearchCoursePromotionCrit;
use App\Modules\Shared\Misc\CoursePromotionViewHelper;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Criteria\Course\CourseReviewSearchCrit;
use App\Modules\Shared\Repositories\Criteria\Course as CourseCrit;
use App\Modules\Shared\Repositories\Criteria\IsCourseCodeActiveCrit;
use App\Modules\Shared\Repositories\Criteria\User as UserCrit;
use App\Modules\Shared\Repositories\Criteria\User\IsUserActiveCrit;
use App\Modules\Shared\Repositories\Criteria\User\UserAccountNotClosedCriteria;
use App\Modules\Subscription\Repositories\Criteria\IsSubscriptionActiveCrit;
use Illuminate\Support\Collection;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class Course
 * @package namespace App\Modules\Content\Repositories;
 */
class Course extends BaseRepository implements CourseRepo {

	const COURSE_OPTION_LEARNERSCOUNT     = 'learnersCount';
	const COURSE_OPTION_WITHINSTITUTENAME = 'withInstituteName';
	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'course_id';

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get Full course detail with user and location information
	 *
	 * @param int  $courseId             Id of the course
	 * @param bool $applyCoursePresenter apply course presenter on the result. Default is true
	 *
	 * @return  collection Returns course detail collection
	 */
	public function getFullCourseDetailWithUserAndLocationByCourseId( $courseId, $applyCoursePresenter = true ) {

		/* Apply course presenter */
		if ( $applyCoursePresenter ) {
			$this->setPresenter(CoursePresenter::class);
		}

		$this->select([
			TABLE_COURSES . '.course_id',
			TABLE_USERS . '.user_school_name',
			TABLE_USERS . '.user_firstname',
			TABLE_USERS . '.user_lastname',
			TABLE_USERS . '.user_picture',
			TABLE_COURSES . '.course_picture',
			TABLE_COURSES . '.course_public_type_id',
			TABLE_COURSES . '.course_name',
			TABLE_COURSES . '.course_is_free',
			TABLE_COURSES . '.course_price',
			TABLE_COURSES . '.course_description',
			TABLE_COURSES . '.course_subtitle',
			TABLE_COURSES . '.course_event_start_date',
			TABLE_COURSE_LOCATION . '.address1',
			TABLE_COURSE_LOCATION . '.address2',
			TABLE_COURSE_LOCATION . '.city',
			TABLE_COURSE_LOCATION . '.pincode',
			TABLE_COUNTRIES . '.countries_name',
			TABLE_STATES . '.state_name',
			TABLE_COURSE_LOCATION . '.state_id',
			TABLE_COURSE_LOCATION . '.country_id'
		]);
		$this->leftjoin(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner')
		     ->leftJoin(TABLE_COURSE_LOCATION, TABLE_COURSE_LOCATION . '.course_id', '=', TABLE_COURSES . '.course_id')
		     ->leftJoin(TABLE_COUNTRIES, TABLE_COUNTRIES . '.countries_id', '=', TABLE_COURSE_LOCATION . '.country_id')
		     ->leftJoin(TABLE_STATES, TABLE_STATES . '.state_id', '=', TABLE_COURSE_LOCATION . '.state_id')
		     ->where(TABLE_COURSES . '.course_id', '=', $courseId);

		$result = $this->first();

		return $this->parserResult($result);
	}

	/**
	 * Get review history of particular course
	 *
	 * @param int $courseId Id of the course
	 *
	 * @return Collection Returns collection of review history
	 */
	public function getCourseReviewHistory( $courseId ) {
		$result = $this->join(TABLE_COURSE_REVIEW_LOG, TABLE_COURSE_REVIEW_LOG . '.course_id', '=', TABLE_COURSES . '.course_id')
		               ->select([
			               TABLE_COURSES . '.course_name',
			               TABLE_COURSE_REVIEW_LOG . '.review_ts',
			               DB::raw("IFNULL(" . TABLE_COURSE_REVIEW_LOG . ".review_notes, '') `review_notes`"),
			               TABLE_COURSE_REVIEW_LOG . '.review_status',
			               DB::raw("case
									    when " . TABLE_COURSE_REVIEW_LOG . ".review_status = " . COURSE_REVIEW_PENDING . " then 'Applied for Review'
									    when " . TABLE_COURSE_REVIEW_LOG . ".review_status = " . COURSE_REVIEW_ACCEPT . " then 'Accepted'
									    when " . TABLE_COURSE_REVIEW_LOG . ".review_status = " . COURSE_REVIEW_REJECT . " then 'Rejected'
									    when " . TABLE_COURSE_REVIEW_LOG . ".review_status = " . COURSE_REVIEW_DEACTIVATE . " then 'Deactivated'
									 end as 'review_status_text'"),
			               DB::raw("DATE_FORMAT(FROM_UNIXTIME(review_ts), '%W %d %M %Y %k:%i:%S') AS review_ts_text")
		               ])
		               ->where(TABLE_COURSES . '.course_id', $courseId)
		               ->orderBy(TABLE_COURSE_REVIEW_LOG . '.review_ts')
		               ->get();

		return $this->parserResult($result);
	}

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
	public function searchCoursesForReview( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT ) {
		$this->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner');
		$this->join(TABLE_COURSE_TYPES, TABLE_COURSE_TYPES . '.course_type_id', '=', TABLE_COURSES . '.course_public_type_id');

		$this->pushCriteria(IsUserActiveCrit::class);
		$this->pushCriteria(UserAccountNotClosedCriteria::class);
		$this->pushCriteria(App::make(CourseReviewSearchCrit::class));

		$this->select([
			TABLE_COURSES . '.course_id',
			TABLE_COURSES . '.course_name',
			TABLE_COURSES . '.course_inserted',
			TABLE_COURSES . '.course_public_type_id',
			DB::raw(TABLE_COURSES . '.course_review_status as course_review_status_code'),
			TABLE_COURSES . '.course_description',
			DB::raw("IFNULL(" . TABLE_COURSES . ".course_comments_reviewer, '') `course_comments_reviewer`"),
			TABLE_USERS . '.user_school_name',
			TABLE_USERS . '.user_mobile',
			TABLE_USERS . '.user_email',
			TABLE_USERS . '.user_website',
			TABLE_COURSE_TYPES . '.course_type',
			DB::raw("case
					    when " . TABLE_COURSES . ".course_review_status = " . COURSE_REVIEW_PENDING . " then 'Review Pending'
					    when " . TABLE_COURSES . ".course_review_status = " . COURSE_REVIEW_ACCEPT . " then 'Accepted'
					    when " . TABLE_COURSES . ".course_review_status = " . COURSE_REVIEW_REJECT . " then 'Rejected'
					    when " . TABLE_COURSES . ".course_review_status = " . COURSE_REVIEW_DEACTIVATE . " then 'Deactivated'
					 end as 'course_review_status'")
		]);

		$this->where(TABLE_COURSES . '.course_review_status', '>', 0)->where(function ( $query ) {
			/* @var Builder $query */
			$query->whereIn(TABLE_COURSES . '.course_status', [COURSE_STATUS_DRAFT, COURSE_STATUS_PUBLISH])
			      ->orWhere(function ( $query ) {
				      /* @var Builder $query */
				      $query->where(TABLE_COURSES . '.course_status', '=', COURSE_STATUS_CLOSE)
				            ->where(TABLE_COURSES . '.course_review_status', '=', COURSE_REVIEW_DEACTIVATE);
			      });
		});
		//Order by is mentioned in old but not used in old query
		//$this->orderBy(TABLE_COURSES.'.course_inserted')->orderBy(TABLE_COURSES.'.course_review_status','desc');

		$result = $paginate ? $this->paginate($pageLength, ['*'], 'page', $pageNo) : $this->get();

		return $this->parserResult($result);
	}

	/**
	 * Search courses for which promotion is created
	 *
	 * @param bool $promotedOnly Show only the course who's promotion is done. default is true
	 * @param bool $paginate     Output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo       Number of the page
	 * @param int  $pageLength   Length of the page
	 *
	 * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection Returns Collection or LengthAwarePaginate
	 * instance containing course list with applied criteria
	 */
	public function searchCoursesForPromotion( $promotedOnly = true, $paginate = true, $pageNo = null,
	                                           $pageLength = PAGINATION_RECORD_COUNT ) {

		$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
		$this->pushCriteria(CourseCrit\IsCourseNotExpiredCrit::class);
		$this->pushCriteria(CourseCrit\IsCourseEntrollmentActiveCrit::class);
		$this->pushCriteria(CourseCrit\IsCoursePublishedCrit::class);
		$this->pushCriteria(CourseCrit\IsPublicCourseCrit::class);
		$this->pushCriteria(SearchCoursePromotionCrit::class);

		$this->select([
			TABLE_COURSES . '.course_id',
			TABLE_COURSES . '.course_name',
			TABLE_COURSES . '.course_price',
			TABLE_COURSES . '.course_public_type_id',
			TABLE_COURSES . '.course_hash',
			TABLE_COURSES . '.course_slug',
			TABLE_COURSE_TYPES . '.course_type',
			TABLE_USERS . '.user_name',
			TABLE_COURSES . '.course_is_free',
			TABLE_COURSE_PRICES . '.amount'
		]);

		$this->leftJoin(TABLE_USERS, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner')
		     ->leftJoin(TABLE_COURSE_TYPES, TABLE_COURSE_TYPES . '.course_type_id', '=', TABLE_COURSES . '.course_public_type_id')
		     ->leftJoin(TABLE_COURSE_PRICES, TABLE_COURSE_PRICES . '.course_id', '=', TABLE_COURSES . '.course_id');

		if ( $promotedOnly ) {

			$this->addSelect([
				TABLE_COURSES . '.course_promotion_rank',
				DB::raw("GROUP_CONCAT(" . TABLE_COURSE_PROMO_LOCATIONS . ".location_name SEPARATOR '<br>') `location_name`")
			]);

			$this->leftJoin(TABLE_COURSE_PROMOTION_BANNERS, TABLE_COURSE_PROMOTION_BANNERS . '.course_id', '=', TABLE_COURSES . '.course_id')
			     ->leftJoin(TABLE_COURSE_PROMO_LOCATIONS, TABLE_COURSE_PROMO_LOCATIONS . '.promo_loc_id', '=', TABLE_COURSE_PROMOTION_BANNERS . '.location_id');

			$this->whereNotNull(TABLE_COURSE_PROMOTION_BANNERS . '.course_promo_loc_id');

			$this->groupBy(TABLE_COURSES . '.course_id')->groupBy(TABLE_COURSE_PRICES . '.amount');

			$this->orderBy(TABLE_COURSES . '.course_id');
		} else {
			$this->orderBy(TABLE_COURSES . '.course_name');
		}


		$result = $paginate ? $this->paginate($pageLength, ['*'], 'page', $pageNo) : $this->get();

		return $this->parserResult($result);
	}

	/**
	 * Get the list of courses
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest Pass true if you are calling this method for auto sugges
	 * @param array  $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getCourses( $text, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(App::make(CourseCrit\IsCourseFreeCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsPublicCourseCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseEntrollmentActiveCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseEnabledCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseNotExpiredCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCoursePublishedCrit::class));

		return $this->getCoursesList(null, $text, $autoSuggest, $attributes, self::COURSE_OPTION_WITHINSTITUTENAME);
	}

	/**
	 * Get the list of courses
	 *
	 * @param null        $ownerId     Id of the course owner
	 * @param null        $text        String you want to match in database
	 * @param bool        $autoSuggest Pass true if you are calling this method for auto suggest
	 * @param array       $attributes  Array of attributes you want from database. Default: course_id, course_name
	 * @param null|string $option      Option to get courses
	 *
	 * @return mixed List of the courses
	 */
	private function getCoursesList( $ownerId = null, $text = null, $autoSuggest = false, array $attributes = [],
	                                 $option = null ) {
		$attrs = ['course_id', 'course_name']; // Default attributes

		// If attributes are specified then we merge the attributes with default attributes
		if ( ! empty($attributes) ) {
			$attrs = array_merge($attrs, $attributes);
		}

		$attrs = $this->addTableToAttrib($this->model(), $attrs);

		// If autoSuggest is true change the name of course_id attribute to id
		if ( $autoSuggest ) {
			$key = array_search($this->model() . '.course_id', $attrs);
			$attrs[$key] = $this->model() . '.course_id as id';
		}

		// Applying necessary criteria
		$this->pushCriteria(App::make(UserCrit\IsUserActiveCrit::class));
		$this->pushCriteria(App::make(UserCrit\UserAccountNotClosedCriteria::class));

		// Sql query started
		$courseSql = $this->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', $this->model() . '.course_owner');

		switch ( $option ) {
			case self::COURSE_OPTION_LEARNERSCOUNT:
				// Query to get count of learners
				$learnersCountSql = DB::table(TABLE_USER_COURSES)
				                      ->select(DB::raw('COUNT(' . TABLE_USER_COURSES . '.user_mod_user_id)'))
				                      ->whereColumn(TABLE_USER_COURSES . '.user_mod_course_id', '=', TABLE_COURSES . '.course_id')
				                      ->where(TABLE_USER_COURSES . '.user_mod_expired', '=', 0)
				                      ->where(TABLE_USER_COURSES . '.user_mod_is_active', '=', 1)
				                      ->where(TABLE_USER_COURSES . '.user_mod_role_id', '=', self::USER_COURSE_ROLE_LEARNER)
				                      ->whereIn(TABLE_USER_COURSES . '.user_mod_user_id', function ( $query ) {
					                      /** @var Builder $query */
					                      $query->select(TABLE_USERS . '.user_id')
					                            ->from(TABLE_USERS)
					                            ->whereColumn(TABLE_USERS . '.user_id', '=', TABLE_USER_COURSES . '.user_mod_user_id')
					                            ->where(TABLE_USERS . '.user_is_active', '=', 1)
					                            ->where(function ( $query ) {
						                            /** @var Builder $query */
						                            $query->whereNull(TABLE_USERS . ".user_acc_closed")
						                                  ->orWhere(TABLE_USERS . '.user_acc_closed', '=', 0);
					                            });
				                      });

				// Add the query to select clause and merge the bindings
				$this->addSelect(DB::raw('(' . $learnersCountSql->toSql() . ') AS `total_learners`'))
				     ->mergeBindings($learnersCountSql);
				break;
			case self::COURSE_OPTION_WITHINSTITUTENAME:
				$this->changeCourseName($attrs);
				break;
			default:
				break;
		}

		if ( ! empty($text) ) {
			$courseSql = $courseSql->where($this->model() . '.course_name', 'LIKE', '%' . $text . '%');
		}

		if ( ! is_null($ownerId) ) {
			$courseSql = $courseSql->where(TABLE_COURSES . '.course_owner', '=', $ownerId);
		}

		// Add the attribute to the select clause
		$this->addSelect($attrs);
		$result = $courseSql->distinct()->orderBy('course_name')->get();

		return $this->parserResult($result);
	}

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
	                                                                     array $attributes = [] ) {
		if ( ! empty($priceType) && in_array($priceType, [
				CoursePromotionViewHelper::COURSE_PAID,
				CoursePromotionViewHelper::COURSE_FREE
			])
		) {
			/* Decrement by one */
			$priceType--;
			$this->where(TABLE_COURSES . '.course_is_free', $priceType);
		}
		if ( ! empty($publicType) && in_array($publicType, [
				ViewHelper::SELECT_COURSE_TYPE_TIME_BOUND,
				ViewHelper::SELECT_COURSE_TYPE_SELF_PACED
			])
		) {
			$this->where(TABLE_COURSES . '.course_public_type_id', $publicType);
		}
		return $this->getInstituteCoursesForPromotion($instituteId, $autoSuggest, $attributes);
	}

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_COURSES;
	}

	/**
	 * Get the course name as concatenation of course name and school name
	 *
	 * @param array $attributes Array of attributes
	 */
	private function changeCourseName( &$attributes ) {
		$nameKey = array_search($this->model() . '.course_name', $attributes);
		$attributes[$nameKey] = DB::raw('CONCAT(' . $this->model() . '.course_name, " (",' . TABLE_USERS . '.user_school_name, ")") AS course_name');
	}

	/**
	 * Get the list of courses of an institute which are active, public, enrollment and course is still not ended.
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getInstituteCoursesForPromotion( $instituteId, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(CourseCrit\IsPublicCourseCrit::class);
		$this->pushCriteria(CourseCrit\IsCourseEntrollmentActiveCrit::class);

		/**@var Collection $result */
		$result = $this->getInstituteCourses($instituteId, $autoSuggest, $attributes);

		if ( ! $result->isEmpty() ) {
			$result = $result->sortBy('id', SORT_NUMERIC);
		}

		return $result;
	}

	/**
	 * Get the list of courses of an institute
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $autoSuggest True if you are calling this method for auto suggest
	 * @param array $attributes  Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed
	 */
	public function getInstituteCourses( $instituteId, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(App::make(CourseCrit\IsCourseEnabledCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseNotExpiredCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCoursePublishedCrit::class));

		return $this->getCoursesList($instituteId, null, $autoSuggest, $attributes);
	}

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
	                                                           array $attributes = [] ) {
		$this->pushCriteria(CourseCrit\FromCourseCopiedCrit::class);

		if ( ! $showDeleted ) {
			$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
			$this->pushCriteria(App::make(CourseCrit\IsCoursePublishedCrit::class));
		}

		return $this->getCoursesList($instituteId, null, $autoSuggest, $attributes);
	}

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
	                                                         array $attributes = [] ) {
		$this->pushCriteria(CourseCrit\ToCourseCopiedCrit::class);
		if ( ! $showDeleted ) {
			$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
			$this->pushCriteria(App::make(CourseCrit\IsCoursePublishedCrit::class));
		}

		return $this->getCoursesList($instituteId, null, $autoSuggest, $attributes);
	}

	/**
	 * Get the free course/s details based on provided id/s
	 *
	 * @param int   $id      Id of the course
	 * @param array $columns Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed Course details
	 */
	public function getFreeCoursesById( $id, array $columns = [] ) {
		$this->pushCriteria(CourseCrit\IsCourseFreeCrit::class);

		return $this->getCoursesById($id, $columns);
	}

	/**
	 * Get the course/s details based on provided id
	 *
	 * @param int   $id         Id of the course
	 * @param array $attributes Array of attributes you want from database. Default: course_id, course_name
	 *
	 * @return mixed Course details
	 */
	public function getCoursesById( $id, $attributes = [] ) {
		$attrs = ['course_id', 'course_name']; // Default attributes

		// If attributes are specified then we merge the attributes with default attributes
		if ( ! empty($attributes) ) {
			$attrs = array_merge($attrs, $attributes);
		}

		$attrs = $this->addTableToAttrib($this->model(), $attrs);

		// Course name is the concatenation of course name and school name
		$this->changeCourseName($attrs);

		// Sql query started
		$this->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', $this->model() . '.course_owner');

		// If multiple ids then returns collection, otherwise array
		if ( ! is_array($id) ) {
			$id = [$id];
			$course = $this->whereIn($this->model() . '.course_id', $id)->first($attrs);
		} else {
			$course = $this->whereIn($this->model() . '.course_id', $id)->get($attrs);
		}


		return $this->parserResult($course);
	}

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
	public function getInstituteCoursesWithLearnerCount( $instituteId, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(CourseCrit\DoesLearnersExistsInCourseCrit::class);
		$this->pushCriteria(App::make(CourseCrit\IsCourseFreeCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseEnabledCrit::class));
		$this->pushCriteria(App::make(CourseCrit\IsCourseNotExpiredCrit::class));
		$courses = $this->getCoursesList($instituteId, null, $autoSuggest, array_merge($attributes, ['course_public']), self::COURSE_OPTION_LEARNERSCOUNT);

		// Add the learner's count and course type to course name
		for ( $index = 0 ; $index < $courses->count() ; $index++ ) {
			$course = $courses->offsetGet($index);
			$course['course_name'] .= ' (' . $course['total_learners'];
			$course['course_name'] .= ' - ' . ($course['course_public'] == 1 ? "Public" : "Private") . ')';
			unset($course['total_learners']);
			unset($course['course_public']);
			$courses->offsetSet($index, $course);
		}

		return $courses;
	}

	/**
	 * Get course and course type details by course id
	 *
	 * @param int   $courseId   Id of the course
	 * @param array $attributes Array of attributes you want from database. Default: course_id, course_name, course_is_free, course_price, amount, course_promotion_rank, course_type
	 *
	 * @return array Course details
	 */
	public function getCourseAndCourseTypeByCourseId( $courseId, $attributes = [] ) {
		$defaultAttributes = [
			TABLE_COURSES . '.course_id',
			TABLE_COURSES . '.course_name',
			TABLE_COURSES . '.course_is_free',
			TABLE_COURSES . '.course_price',
			TABLE_COURSE_PRICES . '.amount',
			TABLE_COURSES . '.course_promotion_rank',
			TABLE_COURSE_TYPES . '.course_type'
		];
		if ( ! empty($attributes) ) {
			$defaultAttributes = array_merge($defaultAttributes, $attributes);
		}
		$this->leftJoin(TABLE_COURSE_TYPES, TABLE_COURSE_TYPES . '.course_type_id', '=', TABLE_COURSES . '.course_public_type_id');
		$this->leftJoin(TABLE_COURSE_PRICES, TABLE_COURSE_PRICES . '.course_id', '=', TABLE_COURSES . '.course_id');
		$result = $this->where(TABLE_COURSES . '.course_id', $courseId)->first($defaultAttributes);

		return $this->parserResult($result);
	}

	/**
	 * Get the number of courses user enrolled in
	 *
	 * @param int   $userId Id of the user
	 * @param array $role   Array of course roles
	 *
	 * @return int Count of enrollment
	 */
	public function getEnrollmentCount( $userId, array $role = null ) {
		$this->pushCriteria(CourseCrit\IsCourseNotExpiredCrit::class);

		$where = [
			[TABLE_USER_COURSES . '.user_mod_is_active', '=', 1],
			[TABLE_USER_COURSES . '.user_mod_expired', '=', 0],
			[TABLE_USER_COURSES . '.user_mod_user_id', '=', (int) $userId]
		];

		$this->join(TABLE_USER_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_USER_COURSES . '.user_mod_course_id')
		     ->where($where);
		if ( ! empty($role) ) {
			if ( is_array($role) ) {
				$this->whereIn(TABLE_USER_COURSES . '.user_mod_role_id', $role);
			} else {
				$this->where(TABLE_USER_COURSES . '.user_mod_role_id', $role);
			}
		}
		$enrollmentCount = $this->count();

		return $enrollmentCount;
	}

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
	] ) {
		$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
		$this->pushCriteria(CourseCrit\IsCoursePublishedCrit::class);
		$this->pushCriteria(IsSubscriptionActiveCrit::class);

		$courses = $this->join(TABLE_USER_COURSES, TABLE_COURSES . '.course_id', '=', TABLE_USER_COURSES . '.user_mod_course_id')
		                ->join(TABLE_USER_COURSE_ROLES, TABLE_USER_COURSES . '.user_mod_role_id', '=', TABLE_USER_COURSE_ROLES . '.user_mod_role_id')
		                ->where(TABLE_USER_COURSES . '.user_mod_user_id', '=', (int) $userId)
		                ->whereIn(TABLE_USER_COURSES . '.user_mod_role_id', $role)
		                ->orderBy(TABLE_COURSES . '.course_name')
		                ->get([TABLE_COURSES . '.course_name', TABLE_USER_COURSES . '.user_mod_role_name as role']);


		return $this->parserResult($courses);
	}

	/**
	 * Get course invitation code of teacher and learner of an institute
	 *
	 * @param int $instituteId Id of institute owner
	 *
	 * @return mixed Collection of invitation code of teacher and learner for an institute
	 */
	public function getCourseCodesOfInstitute( $instituteId ) {
		return $this->getCoursesCode(null, $instituteId);
	}

	/**
	 *
	 * Get teacher and learner invitation codes
	 *
	 * @param int|null $courseId    Course Id
	 * @param int|null $instituteId Id of institute owner
	 *
	 * @return mixed
	 */
	private function getCoursesCode( $courseId = null, $instituteId = null ) {
		$codes = null;
		$this->pushCriteria(CourseCrit\IsCoursePublishedCrit::class);
		$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
		$this->pushCriteria(IsCourseCodeActiveCrit::class);

		$this->distinct()->select([
			TABLE_COURSES . '.course_name',
			DB::raw('(SELECT UCASE(' . TABLE_COURSE_CODES . '.join_code)
                        FROM flt_course_codes
                            WHERE ' . TABLE_COURSE_CODES . '.course_id = ' . TABLE_COURSES . '.course_id
                            AND ' . TABLE_COURSE_CODES . '.code_role_id = ' . self::USER_COURSE_ROLE_TEACHER . '
                            AND ' . TABLE_COURSE_CODES . '.code_is_enabled = 1
                            and ' . TABLE_COURSE_CODES . '.code_is_cancelled = 0 )
                      `teacher_code`'),
			DB::raw('(SELECT UCASE(' . TABLE_COURSE_CODES . '.join_code)
                        FROM flt_course_codes
                            WHERE ' . TABLE_COURSE_CODES . '.course_id = ' . TABLE_COURSES . '.course_id
                            AND ' . TABLE_COURSE_CODES . '.code_role_id = ' . self::USER_COURSE_ROLE_LEARNER . '
                            AND ' . TABLE_COURSE_CODES . '.code_is_enabled = 1
                            and ' . TABLE_COURSE_CODES . '.code_is_cancelled = 0 )
                      `learner_code`')
		])->join(TABLE_COURSE_CODES, TABLE_COURSE_CODES . '.course_id', '=', TABLE_COURSES . '.course_id');

		if ( ! empty($instituteId) ) {
			$this->where(TABLE_COURSES . '.course_owner', '=', $instituteId);
		}

		if ( ! empty($courseId) ) {
			$this->where(TABLE_COURSES . '.course_id', '=', $courseId);
		}

		$codes = $this->orderBy('course_name')->get();

		return $this->parserResult($codes);
	}

	/**
	 * Get course invitation code of teacher and learner of a course
	 *
	 * @param int $courseId Id of course
	 *
	 * @return mixed Collection of invitation code of teacher and learner for an institute
	 */
	public function getCourseCodes( $courseId ) {
		return $this->getCoursesCode($courseId);
	}

	/**
	 * Update the course by course id
	 *
	 * @param int   $courseId provide course id
	 * @param array $data     provide data to update
	 *
	 * @return int Return status of the update operation
	 */
	public function updateCourseByCourseId( $courseId, array $data ) {
		return $this->where('course_id', $courseId)->update($data);
	}

	/**
	 * Get Course price by course id
	 * @param $courseId
	 *
	 * @return array|null|\stdClass
	 */
	public function getCoursePrice( $courseId ) {
		$result = $this->from(TABLE_COURSE_PRICES)->select('amount')->where('course_id', '=', $courseId)->first();

		return $result;
	}
}
