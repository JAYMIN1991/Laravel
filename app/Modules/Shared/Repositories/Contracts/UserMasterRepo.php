<?php

namespace App\Modules\Shared\Repositories\Contracts;

use App\Modules\Shared\Repositories\UserMaster;
use Flinnt\Repository\Contracts\RepositoryCriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface UserMasterRepository
 * @package namespace App\Modules\Shared\Repositories;
 * @see     UserMaster
 */
interface UserMasterRepo extends RepositoryInterface, RepositoryCriteriaInterface {

	/**
	 * Get the user using its login id
	 *
	 * @param string|int $loginId    Email Id or Phone Number of user.
	 * @param array      $attributes Array of attributes you want from database. Default: user_id, user_login
	 *
	 * @return mixed
	 */
	public function getUserByLoginId( $loginId, array $attributes = [] );

	/**
	 * Get the list of users matching where conditions <br>
	 * <b>Note</b>: Array of where should match the l5-repositories findWhere array.
	 * @link     https://github.com/andersao/l5-repository
	 *
	 * @param array $attributes Array of attributes without table name Default : user_id, user_fullname
	 * @param array $where      Array of where conditions
	 *
	 * @return mixed
	 * @internal param int $page
	 */
	public function getUsers( array $attributes = [], array $where = [] );

	/**
	 * Get users for new user page
	 *
	 * @param bool $paginate True will return LengthAwarePaginator
	 *
	 * @return Collection|LengthAwarePaginator
	 */
	public function getUsersForNewUserPage( $paginate = false );

	/**
	 * Get the institute name of owner
	 *
	 * @param int   $ownerId    Id of the institute owner
	 * @param array $attributes Array of attributes you want from database. Default: user_id, user_school_name
	 * @param bool  $active     True will return only active institutes
	 *
	 * @return mixed
	 */
	public function getInstituteByOwnerId( $ownerId, array $attributes = [], $active = true );

	/**
	 * Check if given user id is exist or not
	 *
	 * @param int $id Id of user
	 *
	 * @return bool True if user exists, otherwise false
	 */
	public function userExists( $id );

	/**
	 * Get the institutions list
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getAllInstitutions( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the active institutions list
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstituteList( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the institutions which has at least one course
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getInstitutesHavingCourses( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the institutions from which course/s have been copied
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed List of Institute from which course has been copied
	 */
	public function getInstitutesFromWhichCourseCopied( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * Get the institutions to which course/s have been copied
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed List of Institute from which course has been copied
	 */
	public function getInstitutesToWhichCourseCopied( $text, $autoSuggest = false, array $attributes = [] );

	/**
	 * @param int|null $ownerId     Institute owner Id
	 * @param string   $text        String you want to match in database
	 * @param bool     $autoSuggest True if you are calling this method for auto suggest
	 * @param array    $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getInstitutesListWhoHasPendingCourseReview( $ownerId = null, $text = null, $autoSuggest = false,
	                                                            array $attributes = [] );

	/**
	 * Return the institutions for institute list report page
	 *
	 * @param bool $export   True will return the collection with all the records, otherwise LenghtAwarePaginator
	 * @param bool $paginate True will return the LengthAwarePaginator
	 *
	 * @return LengthAwarePaginator|Collection
	 */
	public function getInstitutionsForReport( $export = false, $paginate = false );

	/**
	 * Get active institution who has public course
	 *
	 * @param null   $ownerId     Institute owner Id
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstituteHavingPublicCoursesByOwnerId( $ownerId = null, array $attributes = []);

	/**
	 * Get active institutions users who has public course
	 *
	 * @param string $text        String you want to match in database
	 * @param bool   $autoSuggest True if you are calling this method for auto suggest
	 * @param array  $attributes  Array of attributes you want from database. Default: user_id, user_school_name
	 *
	 * @return mixed
	 */
	public function getActiveInstitutesHavingPublicCourses( $text = null, $autoSuggest = false, array $attributes = []);

	/**
	 * Get the count of active users
	 *
	 * @return int
	 */
	public function getActiveUsersCount();
}
