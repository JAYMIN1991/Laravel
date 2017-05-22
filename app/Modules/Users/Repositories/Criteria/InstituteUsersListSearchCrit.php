<?php

namespace App\Modules\Users\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class InstituteUsersListSearchCrit
 * @package namespace App\Modules\Users\Repositories\Criteria;
 */
class InstituteUsersListSearchCrit extends AbstractCriteria {

	protected $request;

	/**
	 * InstituteUsersListSearchCrit constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param  BaseRepository     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$instId = (int) GeneralHelpers::clearParam(GeneralHelpers::decode($this->request->get('inst_id')),
			PARAM_RAW_TRIMMED);
		$model->where(TABLE_COURSES . '.course_owner', '=', $instId);

		if ( $this->request->has('first_name') ) {
			$firstName = GeneralHelpers::clearParam($this->request->get('first_name'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_firstname', 'LIKE', '%' . $firstName . '%');
		}

		if ( $this->request->has('user_role_id') ) {
			$roleId = (int) GeneralHelpers::clearParam($this->request->get('user_role_id'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USER_COURSES . '.user_mod_role_id', '=', $roleId);
		}

		if ( $this->request->has('course_id') && $this->request->get('course_id') > 0 ) {
			$courseId = (int) GeneralHelpers::decode(GeneralHelpers::clearParam($this->request->get('course_id'),
				PARAM_RAW_TRIMMED));
			$model->where(TABLE_COURSES . '.course_id', '=', $courseId);
		}

		if ( $this->request->has('user_name') ) {
			$userLogin = GeneralHelpers::clearParam($this->request->get('user_name'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_login', 'LIKE', '%' . $userLogin . '%');
		}

		if ( $this->request->has('user_plan_status') ) {
			$planStatus = (int) GeneralHelpers::clearParam($this->request->get('user_plan_status'), PARAM_RAW_TRIMMED);
			$model->where(TABLE_USERS . '.user_plan_sub_locked', '=', $planStatus - 1);
		}

		return $model;
	}
}
