<?php

namespace App\Modules\Account\Repositories;

use App\Modules\Account\Repositories\Criteria\UserCommissionSearchCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Flinnt\Repository\Criteria\RequestCriteria;
use App\Modules\Account\Repositories\Contracts\UserCommissionDiscountRepo;

/**
 * Class UserCommission
 * @package namespace App\Modules\Account\Repositories;
 * @see     UserCommissionDiscount
 */
class UserCommissionDiscount extends BaseRepository implements UserCommissionDiscountRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'comm_discount_id';

	/**
	 * Get user commission discount data by filter
	 *
	 * @param null $pagination
	 *
	 * @return mixed
	 */
	public function getUserCommissionList( $pagination = null ) {
		$this->pushCriteria(app(UserCommissionSearchCrit::class));
		$this->select([
			TABLE_PAY_COMMISSION_DISCOUNT . '.commission_id',
			TABLE_PAY_COMMISSION_DISCOUNT . '.actual_perc',
			TABLE_PAY_COMMISSION_DISCOUNT . '.applicable_perc',
			TABLE_PAY_COMMISSION_DISCOUNT . '.comm_discount_id',
			TABLE_PAY_COMMISSION_DISCOUNT . '.is_applicable',
			TABLE_USERS . '.user_school_name',
			TABLE_COURSE_TYPES . '.course_type'
		])
		     ->join(TABLE_PAY_COMMISSIONS, TABLE_PAY_COMMISSIONS . '.commission_id', '=', $this->model() . '.commission_id')
		     ->join(TABLE_USERS, TABLE_USERS . '.user_id', '=', $this->model() . '.user_id')
		     ->join(TABLE_COURSE_TYPES, TABLE_COURSE_TYPES . '.course_type_id', '=', TABLE_PAY_COMMISSIONS . '.course_type')
		     ->orderBy('comm_discount_id', 'DESC');

		if ( $pagination ) {
			$result = $this->paginate(PAGINATION_RECORD_COUNT);
		} else {
			$result = $this;
		}

		// Keep this code
		//dd($this->toSql());
		return $this->parserResult($result);
	}

	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_PAY_COMMISSION_DISCOUNT;
	}

	/**
	 * @param $courseTypeId
	 *
	 * @return \Illuminate\Support\Collection
	 * @internal param $id
	 */
	public function getCommissionByCourseTypeId( $courseTypeId ) {
		$results = collect([]);
		if ( $courseTypeId ) {
			$results = DB::table(TABLE_PAY_COMMISSIONS)
			             ->where('course_type', '=', $courseTypeId)
			             ->pluck('commission_id');

			return $results;
		}

		return $results;
	}

	/**
	 * Get data of user commission discount by commission discount id
	 *
	 * @param $commissionDiscountId
	 *
	 * @return mixed
	 */
	public function getUserCommissionDetails( $commissionDiscountId ) {
		$results = $this->where('comm_discount_id', $commissionDiscountId)->first();

		return $this->parserResult($results);
	}

	/**
	 * check data if exist then then update data other wise insert data
	 *
	 * @param $commissionData
	 * @param $checkDataKeys
	 *
	 * @return array|mixed
	 */
	public function createOrUpdateCommissionData( $commissionData, $checkDataKeys ) {
		// check condition for if key data is exist ior not
		$conditions = ['commission_id' => '', 'user_id' => '', 'is_applicable' => ''];
		$conditions = array_intersect_key($commissionData, $conditions);

		$this->applyConditions($conditions);
		$record = $this->orderBy('comm_discount_id', 'DESC')->first();
		$result = [];
		$operation = 'insert';

		if ( empty($record) ) {
			// if key data is not exist then insert new record
			$result = $this->createCommission($commissionData);
		} else {
			// if key data is exist then update with current data
			$result = $this->updateCommissionData($commissionData, $record['comm_discount_id']);
			$operation = 'update';
		}

		$result['operation'] = $operation;

		return $result;
	}

	/**
	 * Insert user commission discount record to DB
	 *
	 * @param $commissionData
	 *
	 * @return mixed
	 */
	public function createCommission( $commissionData ) {
		return $this->create($commissionData);
	}

	/**
	 * update commission data using commission discount id
	 *
	 * @param $commissionDataRecords
	 * @param $commDiscountId
	 *
	 * @return mixed
	 */
	public function updateCommissionData( $commissionDataRecords, $commDiscountId ) {
		$conditions = ['commission_id' => '', 'user_id' => ''];
		$conditions = array_intersect_key($commissionDataRecords, $conditions);
		$this->applyConditions($conditions);
		$record = $this->orderBy('comm_discount_id', 'DESC')->all();

		if ( count($record) > 0 ) {
			if ( $commissionDataRecords['is_applicable'] == 1 ) {
				$commissionExistingData['is_applicable'] = 0;
				foreach ( $record as $key => $value ) {
					$this->updateById($commissionExistingData, $value['comm_discount_id']);
				}
			}
		}

		return $this->updateById($commissionDataRecords, $commDiscountId);
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(app(RequestCriteria::class));
	}
}
