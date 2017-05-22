<?php

namespace App\Modules\Users\Repositories\Criteria;

use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class UserSearchCrit
 * @package namespace App\Modules\Users\Repositories\Criteria;
 */
class UserSearchCrit extends AbstractCriteria {

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * RequestCriteria constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Apply criteria in query repository
	 *
	 * @param BaseRepository      $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		if ( $this->request->has('first_name') ) {
			$firstName = trim($this->request->get('first_name'));
			$model->where($this->getAttributeName(TABLE_USERS, 'user_firstname'), 'LIKE', $firstName . "%");
		}

		if ( $this->request->has('last_name') ) {
			$lastName = trim($this->request->get('last_name'));
			$model->where($this->getAttributeName(TABLE_USERS, 'user_lastname'), 'LIKE', $lastName . "%");
		}

		if ( $this->request->has('user_name') ) {
			$userName = trim($this->request->get('user_name'));
			$model->where($this->getAttributeName(TABLE_USERS, 'user_login'), 'LIKE', '%' . $userName . "%");
		}

		if ( $this->request->has('account_verified') ) {
			$accVerified = (int) $this->request->get('account_verified');
			if ( $accVerified >= 1 ) {
				$model->where($this->getAttributeName(TABLE_USERS, 'user_acc_verified'), '=', $accVerified - 1);
			}
		}

		if ( $this->request->has('user_email') ) {
			$email = trim($this->request->get('user_email'));
			$model->where($this->getAttributeName(TABLE_USERS, 'user_email'), 'LIKE', '%' . $email . "%");
		}

		if ( $this->request->has('user_mobile') ) {
			$mobile = trim($this->request->get('user_mobile'));
			$model->where($this->getAttributeName(TABLE_USERS, 'user_mobile'), 'LIKE', $mobile . "%");
		}

		if ( $this->request->has('deleted_only') && $this->request->get('deleted_only') == 1 ) {
			$model->where($this->getAttributeName(TABLE_USERS, 'user_acc_closed'), '=', 1);
		}

		return $model;
	}
}
