<?php

namespace App\Modules\Users\Repositories;

use App\Common\GeneralHelpers;
use App;
use App\Common\URLHelpers;
use App\Modules\Shared\Repositories\Criteria\Course as CourseCrit;
use App\Modules\Shared\Repositories\Criteria\User as UserCrit;
use App\Modules\Shared\Repositories\Criteria\User\UserAccountNotClosedCriteria;
use App\Modules\Subscription\Repositories\Criteria\IsSubscriptionActiveCrit;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use App\Modules\Users\Repositories\Criteria\InstituteUsersListSearchCrit;
use Cartalyst\Support\Collection;
use DB;
use Exception;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use InvalidArgumentException;

/**
 * Class User
 * @package namespace App\Modules\Users\Repositories;
 */
class User extends BaseRepository implements UserRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'user_id';


	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_USERS;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Return the list of unverified users.
	 *
	 * @param string|null $loginId Login Id of user
	 * @param int         $page    Number of page for pagination
	 *
	 * @return LengthAwarePaginator
	 */
	public function getUnverifiedUsers( $loginId = null, $page = 1 ) {
		$this->pushCriteria(app(UserAccountNotClosedCriteria::class));

		$this->select([
						TABLE_USERS . ".user_firstname",
						TABLE_USERS . ".user_lastname",
						TABLE_USERS . ".user_login",
						TABLE_USER_ACC_VERIFICATIONS . ".verification_code",
						TABLE_USER_ACC_VERIFICATIONS . ".verification_id",
						TABLE_USERS . ".user_acc_auth_mode"
					])
	                ->join(TABLE_USER_ACC_VERIFICATIONS, TABLE_USER_ACC_VERIFICATIONS . ".user_acc_id", "=", TABLE_USERS . ".user_id")
	                ->where([
		                [TABLE_USER_ACC_VERIFICATIONS . ".is_expired", "=", 0],
		                [TABLE_USER_ACC_VERIFICATIONS . ".is_verified", "=", 0],
		                [TABLE_USERS . ".user_acc_verified", "=", 0]
	                ]);

		if ( ! empty($loginId) ) {
			$this->where(TABLE_USERS . ".user_login", "LIKE", "%" . $loginId . "%");
		}

		$this->orderBy(TABLE_USERS . ".user_term_dt", "DESC");

		/** @var LengthAwarePaginator $userPage */
		$userPage = $this->paginate(config('repository.pagination.limit'), ['*'], 'page', $page);

		// Create the verification link if authentication mode is email
		for ( $i = 0 ; $i < count($userPage->items()) ; $i++ ) {
			/** @var Collection $userPage */
			$user = $userPage->offsetGet($i);

			if ( $user["user_acc_auth_mode"] == USER_ACCOUNT_AUTH_MODE_MOBILE ) {
				$user["verification_code_url"] = $user["verification_code"];
			} else {
				$user["verification_code_url"] = '<a href="' . URLHelpers::generateUserVerificationURL($user["verification_id"], $user["user_login"]) . '" target="_blank">Verify</a>';
			}

			$userPage->offsetSet($i, $user);
		}

		return $this->parserResult($userPage);
	}

	/**
	 * Insert the user remarks from backoffice
	 * Data array must have the following data : `remark_text`,`remark_user_id`,`remark_user_inst_id`,
	 * `bkoff_user_id`,`remark_ip`,`remark_dt`,`device_type`
	 *
	 * @param array $data Array of data
	 *
	 * @return int Return the inserted id
	 * @throws \Exception
	 */
	public function insertUserRemarks( array $data ) {
		// Must require key
		$defaultKey = [
			'remark_text',
			'remark_user_id',
			'remark_user_inst_id',
			'bkoff_user_id',
			'remark_ip',
			'remark_dt',
			'device_type'
		];

		// Check if argument array has all the require key
		if ( count(array_intersect_key(array_flip($defaultKey), $data)) === count($defaultKey) ) {
			try {
				return DB::table(TABLE_BACKOFFICE_USER_REMARKS)->insertGetId($data);
			} catch ( Exception $e ) {
				throw $e;
			}
		} else {
			throw new InvalidArgumentException(trans('exception.invalid_argument_array.message',
				['keys' => implode(',', $defaultKey)]), trans('exception.invalid_argument_array.code'));
		}
	}

	/**
	 * Get the list of users of provided institute
	 *
	 * @param bool $paginate True if calling as paginate, otherwise false
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
	 *
	 */
	public function getInstituteUsers( $paginate = false ) {
		DB::statement('SET GROUP_CONCAT_MAX_LEN = 1000000');

		$attrs = [
			'user_id',
			'user_firstname',
			'user_lastname',
			'user_email',
			'user_mobile',
			'user_login',
			'user_mobile_verified',
			'user_email_verified',
		];

		$attrs = $this->addTableToAttrib(TABLE_USERS, $attrs);

		$attrs = array_merge($attrs, [
			DB::raw("CONCAT(" . TABLE_USERS . ".user_firstname, ' ', " . TABLE_USERS . ".user_lastname) `user_name`"),
			DB::raw(
				"GROUP_CONCAT(CONCAT(" . TABLE_COURSES . ".course_name, ' (', " . TABLE_USER_COURSE_ROLES . ".user_mod_role_name, ')') ORDER BY " . TABLE_COURSES . ".course_name, 
				" . TABLE_USER_COURSE_ROLES . ".user_mod_role_name SEPARATOR ', ' ) `course_name`"
			),
		    DB::raw(
		    	"CASE WHEN " . TABLE_USER_COURSES . ".user_mod_user_id 
		        IN (SELECT dr.user_id FROM flt_device_registrations dr) 
		        THEN 'Yes' ELSE 'No' END `mobile_user`"
		    ),
		    DB::raw("CASE WHEN " . TABLE_USERS . ".user_acc_verified = 1 THEN 'Yes' ELSE 'No' END `verified_user`"),
		    DB::raw("CASE WHEN " . TABLE_USERS . ".user_acc_verified = 1 
		        THEN DATE_FORMAT(FROM_UNIXTIME(" . TABLE_USERS . ".user_acc_verify_ts), '%d/%m/%Y') ELSE '' END 
		        `verification_date`"
		    ),
		    DB::raw("DATE_FORMAT(FROM_UNIXTIME(" . TABLE_USERS . ".user_term_dt), '%d/%m/%Y') `user_term_dt`")
		]);

		$this->pushCriteria(App::make(InstituteUsersListSearchCrit::class));
		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);
		$this->pushCriteria(UserCrit\IsTestingUserCrit::class);
		$this->pushCriteria(CourseCrit\IsCourseEnabledCrit::class);
		$this->pushCriteria(CourseCrit\IsCoursePublishedCrit::class);
		$this->pushCriteria(IsSubscriptionActiveCrit::class);

		$this->select($attrs)
		              ->join(TABLE_USER_COURSES,
			              TABLE_USER_COURSES . '.user_mod_user_id',
			              '=',
			              TABLE_USERS . '.user_id')
		              ->join(TABLE_COURSES,
			              TABLE_COURSES . '.course_id',
			              '=',
			              TABLE_USER_COURSES . '.user_mod_course_id')
		              ->join(TABLE_USER_COURSE_ROLES,
			              TABLE_USER_COURSE_ROLES . '.user_mod_role_id',
			              '=',
			              TABLE_USER_COURSES . '.user_mod_role_id')
		              ->leftJoin(TABLE_COURSE_CODES, function ( $join ){
		              	    /** @var JoinClause $join */
			              $join->on(TABLE_COURSE_CODES . '.course_id', '=', TABLE_COURSES . '.course_id');
			              $join->on(TABLE_COURSE_CODES . '.code_role_id', '=', TABLE_USER_COURSES . '.user_mod_role_id');
			              $join->on(TABLE_COURSE_CODES . '.code_is_enabled', '=', DB::raw(1));
			              $join->on(TABLE_COURSE_CODES . '.code_is_cancelled', '=', DB::raw(0));
			          })->whereRaw('IFNULL(' . TABLE_USERS . '.user_plan_id, 0) <> 1')
		              ->groupBy('user_id')
		              ->orderBy('user_firstname')
		              ->distinct();

		if ($paginate){
			$users = $this->paginate(config('repository.pagination.limit'));
		} else {
			$users = $this->get();
		}

		return $this->parserResult($users);
	}

	/**
	 * Get the acquired institute detail by institute id
	 *
	 * @param int   $instituteId Id of the institute
	 * @param bool  $withEmail   If true, will return institute name with email id
	 * @param array $columns     List of columns to be retrieved
	 *
	 * @return array
	 */
	public function getAcquiredInstituteById( $instituteId, $withEmail = false, array $columns = [] ) {
		return $this->getInstituteOfInquiry(null, false, $instituteId, $withEmail, $columns);
	}

	/**
	 * @param null|string  $term Search term
	 * @param bool  $autoSuggest  if true, will return column as id instead of user_id
	 * @param bool  $withEmailId If true, will return institute name with email id
	 * @param array $columns List of columns to be retrieved
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getNotAcquiredInstituteList( $term = null, $autoSuggest = false, $withEmailId = false, $columns = [] ) {
		return $this->getInstituteOfInquiry($term, $autoSuggest, null, $withEmailId, $columns);
	}

	/**
	 * Get list for Institute drop-down
	 *
	 * @param string $term           specify term to filter name
	 * @param bool   $autoSuggest
	 * @param int    $selectedInstId specify institute to be included
	 * @param bool   $withEmailId    output school name with institute email id
	 * @param array  $columns        Columns to fetch from database default : `user_id`, `user_school_name`
	 *
	 * @return array|\Illuminate\Support\Collection
	 */
	  private function  getInstituteOfInquiry( $term = null, $autoSuggest = false, $selectedInstId = null, $withEmailId = false, $columns = [] ) {
		$defaultColumns = ['user_id', 'user_school_name'];
		$selectedInstId = GeneralHelpers::clearParam($selectedInstId, PARAM_RAW_TRIMMED);

		if (!empty($columns)) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		if ($autoSuggest) {
			$index = array_search('user_id', $defaultColumns);
			$defaultColumns[$index] = 'user_id as id';
		}

		if ( $withEmailId ) {
			$index = array_search('user_school_name', $defaultColumns);
			$defaultColumns[$index] = DB::raw("CONCAT(user_school_name, ' (', user_login, ')') as user_school_name");
		}
		if ( $term != null ) {
			$this->where('user_school_name', 'LIKE', "%" . GeneralHelpers::clearParam($term, PARAM_RAW_TRIMMED) . "%");
		}

		 if ( ! empty($selectedInstId) ) {

			 $this->orWhereIn('user_id', function ( $query ) use ( $selectedInstId ) {
				 /** @var Builder $query */
				 $query->select(TABLE_BACKOFFICE_INST_INQUIRY . '.converted_inst_id')
				       ->from(TABLE_BACKOFFICE_INST_INQUIRY)
				       ->where(TABLE_BACKOFFICE_INST_INQUIRY . '.acq_status', '=', 1)
				       ->where(TABLE_BACKOFFICE_INST_INQUIRY . '.converted_inst_id', '=', $selectedInstId);
			 });

			 $result = $this->first($defaultColumns);

			 return $this->parserResult($result);
		 }

		$result = $this->whereRaw('IFNULL(user_school_name,"") <> ?', [""])
		               ->where('user_plan_verified','=', 1)
		               ->where('user_plan_expired','=', 0)
		               ->where(function ( $query ) {
							/** @var Builder $query */
							$query->whereNull('user_acc_closed')
								->orWhere('user_acc_closed', '=', 0);
						})
		               ->whereRaw('user_id NOT IN ('
						.'							SELECT'
						.'								converted_inst_id'
						.'							FROM ' . TABLE_BACKOFFICE_INST_INQUIRY
						.'							WHERE'
						.'								converted_inst_id IS NOT NULL ) ')
		               ->orderBy('user_school_name')
		               ->get($defaultColumns);

		return $this->parserResult($result);
	}

	/**
	 * Get list of institute for after sales visit
	 *
	 * @param string $term           specify term to filter name
	 * @param bool   $autoSuggest    if true, will return column as id instead of user_id
	 * @param bool   $withEmailId    output school name with institute email id
	 * @param bool   $afterSalesOnly true means only show institute where after sales visit entry exists
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getInstituteListForAfterSalesVisit( $term = null, $autoSuggest = false ,$withEmailId = false, $afterSalesOnly = false) {
		//TODO: try to merge this method with getInstituteOfInquiry()
		$defaultColumns = ['user_id as id'];
		$this->distinct();

		if ( $term != null ) {
			$this->where('user_school_name', 'LIKE', "%" . GeneralHelpers::clearParam($term, PARAM_RAW_TRIMMED) . "%");
		}

		if ( $withEmailId ) {
			$defaultColumns[] = DB::raw("CONCAT(user_school_name, ' (', user_login, ')') as user_school_name");
		} else {
			$defaultColumns[] = "user_school_name";
		}

		/* show only the institute which has after sales visit entry */
		if($afterSalesOnly)
		{
			$this->rightJoin(TABLE_BACKOFFICE_AFTER_SALES_VISIT, TABLE_BACKOFFICE_AFTER_SALES_VISIT.'.inst_user_id','=',TABLE_USERS.'.user_id');
		}

		$result = $this->whereRaw('IFNULL(user_school_name,"") <> ?',[""])
		               ->where('user_plan_verified','=',1)
		               ->where('user_plan_expired','=',0)
					   ->where(function($query){
					   	    $query->whereNull('user_acc_closed')
						          ->orWhere('user_acc_closed','=',0);
					   })
		               ->orderBy('user_school_name')
		               ->get($defaultColumns);

		return $this->parserResult($result);
	}

	/**
	 * Get the institute name of the user
	 *
	 * @param int $userId Id of the user
	 *
	 * @return mixed Return the name of the the user in collection
	 */
	public function getInstituteNameFromUserCoursesTable( $userId ) {
		$attrs = ['user_school_name'];
		$attrs = $this->addTableToAttrib($this->model(), $attrs);

		$where = [
			[TABLE_COURSES . '.course_enabled', '=', 1],
			[TABLE_COURSES . '.course_status', '=', COURSE_STATUS_PUBLISH],
			[TABLE_COURSES . '.course_public', '=', 0],
			[TABLE_USER_COURSES . '.user_mod_expired', '=', 0],
			[TABLE_USER_COURSES . '.user_mod_is_active', '=', 1]
		];

		$result = $this->select($attrs)
		               ->join(TABLE_COURSES, TABLE_COURSES . '.course_owner', '=', TABLE_USERS . '.user_id')
		               ->join(TABLE_USER_COURSES, TABLE_COURSES . '.course_id', TABLE_USER_COURSES . '.user_mod_course_id')
		               ->where($where)
		               ->where(TABLE_USER_COURSES . '.user_mod_user_id', '=', $userId)
		               ->orderBy(TABLE_USER_COURSES . '.user_mod_course_id')
		               ->first();

		return $this->parserResult($result);
	}
}
