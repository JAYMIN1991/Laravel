<?php

namespace App\Modules\Shared\Repositories;

use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Shared\Repositories\Criteria\Course\FromCourseCopiedCrit;
use App\Modules\Shared\Repositories\Criteria\Course\IsCourseEnabledCrit;
use App\Modules\Shared\Repositories\Criteria\Course\IsCoursePublishedCrit;
use App\Modules\Shared\Repositories\Criteria\Course\IsPublicCourseCrit;
use App\Modules\Shared\Repositories\Criteria\Course\ToCourseCopiedCrit;
use App\Modules\Shared\Repositories\Criteria\DoesInstituteHasCoursesCrit;
use App\Modules\Shared\Repositories\Criteria\User as UserCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class UserMaster
 * @package namespace App\Modules\Shared\Repositories;
 */
class UserMaster extends BaseRepository implements UserMasterRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'user_id';

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_USERS;
	}

	/**
	 * Get the user using its login id
	 *
	 * @param string|int $loginId    Email Id or Phone Number of user.
	 * @param array      $attributes Array of attributes without table name. Default: user_id, user_login
	 *
	 * @return mixed
	 */
	public function getUserByLoginId( $loginId, array $attributes = [] ) {
		$attrs = ['user_id', 'user_login'];

		if ( ! empty($attributes) ) {
			$attrs = array_merge($attrs, $attributes);
		}

		$attrs = $this->addTableToAttrib($this->model(), $attrs);
		$user = $this->where('user_login', Str::lower($loginId))->first($attrs);

		return $this->parserResult($user);
	}

	/**
	 * Check if given user id is exist or not
	 *
	 * @param int $id Id of user
	 *
	 * @return bool True if user exists, otherwise false
	 */
	public function userExists( $id ) {

		$user = $this->find($id);
		if ( isset($user['user_id']) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the list of users matching where conditions <br>
	 * <b>Note</b>: Array of where should match the l5-repositories findWhere array.
	 * @link     https://github.com/andersao/l5-repository
	 *
	 * @param array    $attributes Array of attributes without table name Default : user_id, user_fullname
	 * @param array    $where      Array of where conditions
	 * @param int|null $page       Specify number of page if you are not using LengthAwarePaginator
	 *
	 * @return mixed
	 *
	 */
	public function getUsers( array $attributes = [], array $where = [], $page = null ) {
		$attrs = ['user_id', 'user_fullname']; // Default Attributes

		// If attributes are specified then we merge the attributes with default attributes
		if ( ! empty($attributes) ) {
			$attrs = array_merge($attrs, $attributes);
		}

		// Add the table to where attributes and apply the where condition to query
		if ( ! empty($where) ) {
			$this->addTableToWhere($this->model(), $where);
			$this->applyConditions($where);
		}

		$attrs = $this->addTableToAttrib($this->model(), $attrs);

		// Get the full name of user
		$this->getFullName($attrs);

		//Applying the necessary criteria
		$this->pushCriteria(app(UserCrit\IsTestingUserCrit::class));

		array_push($attrs, DB::raw("IF ( " . $this->model() . ".user_acc_closed = 1 , 'Deleted', 
											 IF ( " . $this->model() . ".user_is_active = 1, 'Active', 
											 IF ( " . $this->model() . ".user_acc_verified = 1, 'Inactive', 'Verification Pending'
											 ))) `account_status`"));

		$userSql = $this->select($attrs);
		$userSql = $userSql->orderBy($this->model() . '.user_firstname');

		if ( ! is_null($page) && is_numeric((int) $page) ) {
			$users = $userSql->paginate(config('repository.pagination.limit'), ['*'], 'page', $page);
		} else {
			$users = $userSql->paginate(config('repository.pagination.limit'));
		}

		return $this->parserResult($users);
	}

	/**
	 * Get users for new user page
	 *
	 * @param bool $paginate True will return LengthAwarePaginator
	 *
	 * @return Collection|LengthAwarePaginator
	 */
	public function getUsersForNewUserPage( $paginate = false ) {
		$this->pushCriteria(UserCrit\IsTestingUserCrit::class);
		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);
		DB::statement('SET GROUP_CONCAT_MAX_LEN = 1000000');

		$columns = [
			TABLE_USERS . '.user_id',
			DB::raw('CONCAT(' . TABLE_USERS . '.user_firstname, \' \', ' . TABLE_USERS . '.user_lastname) `user_name`'),
			TABLE_USERS . '.user_mobile',
			TABLE_USERS . '.user_login',
			TABLE_USERS . '.user_school_name',
			DB::raw('(SELECT u1.user_school_name 
						FROM ' . TABLE_USERS . ' as u1 
						WHERE u1.user_id = ( SELECT course_owner 
											 FROM ' . TABLE_COURSES . '
											 WHERE course_enabled = 1 
											    AND course_status = ' . COURSE_STATUS_PUBLISH . '
											    AND course_id = ( SELECT user_mod_course_id 
											                        FROM ' . TABLE_USER_COURSES . '
											                        WHERE user_mod_expired = 0 
											                            AND user_mod_is_active = 1 
											                            AND user_mod_user_id = ' . TABLE_USERS . '.user_id
											                            ORDER BY user_mod_course_id DESC LIMIT 1 )
											    LIMIT 1)
		                ) institute'),
			DB::raw('(SELECT GROUP_CONCAT(CONCAT(c.course_name, \' (\', ur.`user_mod_role_name`, \')\') ORDER BY c.`course_name`,
                       ur.`user_mod_role_name` SEPARATOR \', \') `course_name`
   						FROM flt_courses c
     					JOIN flt_user_courses uc ON uc.`user_mod_course_id` = c.course_id
     					JOIN flt_user_course_roles ur ON ur.`user_mod_role_id` = uc.`user_mod_role_id`
   							WHERE uc.`user_mod_user_id` = ' . TABLE_USERS . '.user_id 
   								AND c.course_enabled = 1 
   								AND c.course_status = ' . COURSE_STATUS_PUBLISH . ' 
   								AND uc.user_mod_expired = 0 
   								AND uc.user_mod_is_active = 1) `courses`'),
		];

		$this->select($columns)
		     ->orderBy('institute')
		     ->orderBy(TABLE_USERS . '.user_school_name')
		     ->orderBy(TABLE_USERS . '.user_firstname');

		if ( $paginate ) {
			$result = $this->paginate();
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}

	/**
	 * Get the institute name of the user
	 *
	 * @param int $userId Id of the user
	 *
	 * @return mixed Return the name of the the user in collection
	 */
	public function getInstituteName( $userId ) {
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
		               ->join(TABLE_USER_COURSES, TABLE_COURSES . '.course_id',
			               TABLE_USER_COURSES . '.user_mod_course_id')
		               ->where($where)
		               ->where(TABLE_USER_COURSES . '.user_mod_user_id', '=', $userId)
		               ->orderBy(TABLE_USER_COURSES . '.user_mod_course_id')
		               ->first();

		return $this->parserResult($result);
	}

	/**
	 * Get the institutions list
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getAllInstitutions( $text, $autoSuggest = false, array $attributes = [] ) {
		return $this->getInstitutes(null, $text, $autoSuggest, $attributes);
	}

	/**
	 * Get the active institutions list
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstituteList( $text, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutes(null, $text, $autoSuggest, $attributes);
	}

	/**
	 * Get the institute name of owner
	 *
	 * @param int   $ownerId    Id of the institute owner
	 * @param array $attributes Array of attributes you want from database. Default: user_id, user_school_name
	 * @param bool  $active     True will return only active institutes
	 *
	 * @return mixed
	 */
	public function getInstituteByOwnerId( $ownerId, array $attributes = [], $active = true ) {

		if ( $active ) {
			$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);
		}

		return $this->getInstitutes($ownerId, null, false, $attributes);
	}

	/**
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed List of Institute from which course has been copied
	 */
	public function getInstitutesFromWhichCourseCopied( $text, $autoSuggest = false, array $attributes = [] ) {
		$this->distinct()->join(TABLE_COURSES, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner');
		$this->pushCriteria(FromCourseCopiedCrit::class);
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutes(null, $text, $autoSuggest, $attributes);
	}

	/**
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed List of Institute from which course has been copied
	 */
	public function getInstitutesToWhichCourseCopied( $text, $autoSuggest = false, array $attributes = [] ) {
		$this->distinct()->join(TABLE_COURSES, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner');
		$this->pushCriteria(ToCourseCopiedCrit::class);
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutes(null, $text, $autoSuggest, $attributes);
	}

	/**
	 * Get the institutions which has at least one course
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getInstitutesHavingCourses( $text, $autoSuggest = false, array $attributes = [] ) {
		$this->pushCriteria(DoesInstituteHasCoursesCrit::class);
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutes(null, $text, $autoSuggest, $attributes);
	}

	/**
	 * Get active institution who has public course
	 *
	 * @param null   $ownerId     Institute owner Id
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstituteHavingPublicCoursesByOwnerId( $ownerId = null, array $attributes = []){
		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);
		$this->pushCriteria(UserCrit\UserAccountNotClosedCriteria::class);
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutesHavingPublicCourses( $ownerId, null , false, $attributes);
	}

	/**
	 * Get active institutions users who has public course
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstitutesHavingPublicCourses( $text = null, $autoSuggest = false, array $attributes = []){
		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);
		$this->pushCriteria(UserCrit\UserAccountNotClosedCriteria::class);
		$this->pushCriteria(UserCrit\IsInstituteActiveCrit::class);

		return $this->getInstitutesHavingPublicCourses( null, $text , $autoSuggest, $attributes);

	}

	/**
	 * Get institutions users who has public course
	 *
	 * @param null   $ownerId     Institute owner Id
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	private function getInstitutesHavingPublicCourses( $ownerId = null, $text = null, $autoSuggest = false,
	                                                         array $attributes = [] ) {

		$this->distinct();

		$this->join(TABLE_COURSES, TABLE_COURSES . '.course_owner', '=', TABLE_USERS . '.user_id');
		$this->pushCriteria(IsPublicCourseCrit::class);


		return $this->getInstitutes($ownerId, $text, $autoSuggest, $attributes);
	}

	/**
	 * Return the institutions for institute list report page
	 *
	 * @param bool $export   True will return the collection with all the records, otherwise LenghtAwarePaginator
	 * @param bool $paginate True will return the LengthAwarePaginator
	 *
	 * @return LengthAwarePaginator|Collection
	 */
	public function getInstitutionsForReport( $export = false, $paginate = false ) {
		$this->pushCriteria(UserCrit\UserAccountNotClosedCriteria::class);

		$this->select([
			TABLE_USERS . '.user_id',
			DB::raw('CONCAT(
					' . TABLE_USERS . '.user_firstname, " ", ' . TABLE_USERS . '.user_lastname, 
					" - ", 
					IFNULL(' . TABLE_USERS . '.user_school_name, "")) 
				as `user_name`'),
			TABLE_USERS . '.user_email',
			TABLE_USERS . '.user_mobile',
			TABLE_USERS . '.user_login',
			TABLE_USERS . '.user_school_name',
			TABLE_SUBSCRIPTION_PLANS . '.plan_price',
			TABLE_USERS . '.user_plan_trial',
			TABLE_USERS . '.user_name as public_page_name',
			DB::raw('(SELECT DATE_FORMAT(FROM_UNIXTIME(' . TABLE_USER_ACC_HISTORY . '.sub_hist_dt), "%d/%m/%Y") `reg_date`
                        FROM ' . TABLE_USER_ACC_HISTORY . '
                        WHERE ' . TABLE_USER_ACC_HISTORY . '.sub_hist_user_id = ' . TABLE_USERS . '.user_id 
                            AND LOWER(' . TABLE_USER_ACC_HISTORY . '.sub_hist_action) IN ("planactive", "trialplanactive")
                        ORDER BY sub_hist_dt DESC LIMIT 1) 
                    as `reg_date`'),
			DB::raw('CASE WHEN ' . TABLE_USERS . '.user_plan_trial = 1 THEN "TRIAL" ELSE "FULL" END 
		            as `plan_type`'),
			DB::raw('IFNULL((SELECT COUNT(i.invoice_id) FROM flt_invoices i WHERE i.invoice_user_id = ' . TABLE_USERS . '.user_id), 0) 
					as `total_invoices`'),
			DB::raw('IFNULL(' . TABLE_USERS . '.user_plan_verified, 0) 
					as `user_plan_verified`'),
			DB::raw('IFNULL(' . TABLE_USERS . '.user_plan_cancelled, 0) 
		            as `user_plan_cancelled`'),
			DB::raw('IFNULL(' . TABLE_USERS . '.user_plan_expired, 0) 
		            as `user_plan_expired`'),
			DB::raw('IFNULL(
						(SELECT GROUP_CONCAT(CONCAT(UCASE(SUBSTR(l.action, 1, INSTR(l.action, "_") - 1)), ": ", l.info) 
								ORDER BY l.user_datetime ASC)
	                        FROM flt_backoffice_log l
	                        WHERE l.module_id = ' . TABLE_USERS . '.user_id 
	                        AND l.`action` IN ("verify_plan", "cancel_plan") 
	                        AND info <> ""),"") 
                    as `remarks`'),
			DB::raw('(SELECT st.first_name
                        FROM flt_backoffice_sales_team st 
                        LEFT JOIN flt_backoffice_inst_inquiry ii ON ii.acq_member_id = st.member_id
                        WHERE ii.converted_inst_id = ' . TABLE_USERS . '.user_id) 
                        as `sales_by`'),
			DB::raw('(SELECT st1.virtual_member
						   FROM flt_backoffice_sales_team st1 
						   LEFT JOIN flt_backoffice_inst_inquiry ii1 ON ii1.acq_member_id = st1.member_id
                           WHERE ii1.converted_inst_id = ' . TABLE_USERS . '.user_id) 
                           as `virtual_member`'),
			DB::raw('(SELECT COUNT(DISTINCT user_mod_user_id) FROM ' . TABLE_USER_COURSES . ' uc, ' . TABLE_COURSES . ' c
							WHERE uc.user_mod_course_id = c.course_id
							AND c.course_owner = ' . TABLE_USERS . '.user_id
							AND c.course_status = ' . COURSE_STATUS_PUBLISH . ' AND c.course_enabled = 1
							AND uc.user_mod_expired = 0 AND uc.user_mod_is_active = 1) `total_users`'),
		])
		     ->join(TABLE_SUBSCRIPTION_PLANS, TABLE_SUBSCRIPTION_PLANS . '.plan_id', '=', TABLE_USERS . '.user_plan_id')
		     ->whereRaw('IFNULL(' . TABLE_USERS . '.user_school_name, \'\') <> \'\'');

		if ( $export ) {
			$this->addSelect([
				DB::raw('(SELECT COUNT(DISTINCT dr.`user_id`) 
			                FROM ' . TABLE_USER_COURSES . ' uc, ' . TABLE_COURSES . ' c, ' . TABLE_DEVICE_REGISTRATIONS . ' dr
							WHERE uc.user_mod_course_id = c.course_id AND dr.`user_id` = uc.`user_mod_user_id`
							AND c.course_owner = ' . TABLE_USERS . '.user_id
							AND c.course_status = ' . COURSE_STATUS_PUBLISH . ' AND c.course_enabled = 1
							AND uc.user_mod_expired = 0 AND uc.user_mod_is_active = 1) `mobile_users`'),
			]);
		}

		$this->orderBy(TABLE_USERS . '.user_school_name');

		if ( $paginate ) {
			$result = $this->paginate();
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}

	/**
	 * Get the count of active users
	 *
	 * @return int
	 */
	public function getActiveUsersCount() {

		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);

		return $this->getUsersCount();
	}

	/**
	 * Get the total count of users
	 *
	 * @return int
	 */
	private function getUsersCount() {

		return $this->count();
	}

	/**
	 * @param int|null $ownerId     Institute owner Id
	 * @param string   $text        String you want to match in database
	 * @param bool     $autoSuggest True if you are calling this method for auto suggest
	 * @param array    $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getInstitutesListWhoHasPendingCourseReview( $ownerId = null, $text = null, $autoSuggest = false,
	                                                            array $attributes = [] ) {
		$this->distinct()->join(TABLE_COURSES, TABLE_USERS . '.user_id', '=', TABLE_COURSES . '.course_owner');
		$this->pushCriteria(IsCourseEnabledCrit::class);
		$this->pushCriteria(IsPublicCourseCrit::class);
		$this->pushCriteria(UserCrit\IsUserActiveCrit::class);
		$this->pushCriteria(UserCrit\UserAccountNotClosedCriteria::class);

		return $this->getInstitutes($ownerId, $text, $autoSuggest, $attributes);
	}

	/**
	 * Get institution
	 *
	 * @param null   $ownerId     Institute owner Id
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	private function getInstitutes( $ownerId = null, $text = null, $autoSuggest = false, array $attributes = [] ) {
		$attrs = ['user_id', 'user_school_name'];
		$this->pushCriteria(UserCrit\IsUserInstituteCrit::class);

		// If auto suggest is true, change the user_id to id
		if ( $autoSuggest ) {
			$key = array_search($this->model() . ".user_id", $attrs);
			$attrs[$key] = $this->model() . '.user_id as id';
		}

		// If attributes is specified merge it with pre-defined attributes
		if ( isset($attributes[0]) ) {
			$attrs = array_merge($attrs, $attributes);
			$attrs = $this->addTableToAttrib($this->model(), $attrs);
		}

		// Add the condition to query based on provided text
		if ( ! empty($text) ) {
			$this->where(TABLE_USERS . '.user_school_name', 'LIKE', '%' . $text . '%');
		}

		// Add the condition if owner is specified
		if ( ! empty($ownerId) ) {
			$result = $this->where(TABLE_USERS . '.user_id', '=', $ownerId)->first($attrs);

			return $this->parserResult($result);
		}

		$result = $this->orderBy(TABLE_USERS . '.user_school_name')->get($attrs);

		return $this->parserResult($result);
	}

	/**
	 * Change the full name to concat first name and last name.
	 *
	 * @param array $attributes Array of attributes
	 */
	private function getFullName( array &$attributes ) {
		$nameKey = array_search($this->model() . '.user_fullname', $attributes);

		if ( $nameKey != false ) {
			$attributes[$nameKey] = DB::raw("CONCAT(" . $this->model() . ".user_firstname, ' ', " . $this->model() . ".user_lastname) `user_fullname`");
		}
	}
}
