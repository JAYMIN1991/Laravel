<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Shared\Repositories\Course;
use App\Modules\Subscription\Repositories\Contracts\CourseSubscriptionRepo;
use App\Modules\Subscription\Repositories\Criteria\IsSubscriptionActiveCrit;
use DB;
use Exception;
use Flinnt\Repository\Criteria\RequestCriteria;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

/**
 * Class CourseSubscription
 * @package namespace App\Modules\Subscription\Repositories;
 */
class CourseSubscription extends BaseRepository implements CourseSubscriptionRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'id';

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_USER_COURSES;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(app(RequestCriteria::class));
	}

	/**
	 * Get the count of users of institute
	 *
	 * @param int   $instituteId Id of institute owner
	 * @param array $roles       Array of roles
	 *
	 * @return int Count of users
	 */
	public function getInstituteUserCount( $instituteId, array $roles ) {

		$this->pushCriteria(IsSubscriptionActiveCrit::class);
		$count = $this->whereIn(TABLE_USER_COURSES . '.user_mod_role_id', $roles)->whereRaw(TABLE_USER_COURSES . ".user_mod_course_id IN ( 
				SELECT " . TABLE_COURSES . ".course_id 
					FROM " . TABLE_COURSES . " 
						WHERE " . TABLE_COURSES . ".course_enabled = 1 
							AND " . TABLE_COURSES . ".course_status = " . COURSE_STATUS_PUBLISH . " 
							AND " . TABLE_COURSES . ".course_owner = '" . (int) $instituteId . "'
							)")->distinct()->count(TABLE_USER_COURSES . '.user_mod_user_id');

		return $count;
	}

	/**
	 * Get list of users who have subscribed in from course as learner and not subscribed
	 * in to course by any role
	 *
	 * @param int $fromCourse From course Id
	 * @param int $toCourse   To course Id
	 *
	 * @return mixed
	 */
	public function getUserWithSubscription( $fromCourse, $toCourse ) {
		$cols = [TABLE_USERS . '.user_id'];
		$this->pushCriteria(IsSubscriptionActiveCrit::class);

		$users = $this->select($cols)
		              ->join(TABLE_USERS, function ( $join ) {
			              /** @var JoinClause $join */
			              $join->on(TABLE_USERS . '.user_id', '=', TABLE_USER_COURSES . '.user_mod_user_id');
			              $join->where(TABLE_USERS . '.user_is_active', '=', 1);
			              $join->on(function ( $query ) {
				              /** @var Builder $query */
				              $query->where(TABLE_USERS . '.user_acc_closed', '=', 0);
				              $query->orWhereNull(TABLE_USERS . '.user_acc_closed');
			              });
		              })
		              ->where(TABLE_USER_COURSES . '.user_mod_course_id', '=', $fromCourse)
		              ->where(TABLE_USER_COURSES . '.user_mod_role_id', '=', Course::USER_COURSE_ROLE_LEARNER)
		              ->whereNotIn(TABLE_USER_COURSES . '.user_mod_user_id', function ( $query ) use ( $toCourse ) {
			              /** @var Builder $query */
			              $query->select('uci.user_mod_user_id')
			                    ->from(TABLE_USER_COURSES . ' as uci')
			                    ->whereColumn('uci.user_mod_user_id', TABLE_USER_COURSES . '.user_mod_user_id')
			                    ->where('uci.user_mod_expired', '=', 0)
			                    ->where('uci.user_mod_is_active', '=', 1)
			                    ->where('uci.user_mod_course_id', '=', $toCourse);
		              })
		              ->get();

		return $this->parserResult($users);
	}
}
