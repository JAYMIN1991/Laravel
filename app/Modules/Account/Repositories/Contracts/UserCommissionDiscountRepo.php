<?php

namespace App\Modules\Account\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserCommissionDiscountRepo
 * @package namespace App\Modules\Account\Repositories\Contracts;
 * @see UserCommissionDiscountRepo
 */
interface UserCommissionDiscountRepo extends RepositoryInterface
{
    /**
     * get user commission data from commission discount as per filter
     * @param null $pagination
     * @return mixed
     */
    public function getUserCommissionList($pagination = null);

    /**
     * Insert user commission data in to DB
     * @param $commissionData
     * @return mixed
     * @internal param $data
     */
    public function createCommission($commissionData);

    /**
     * Get user commission id using course type id
     * @param $courseTypeId
     * @return mixed
     */
    public function getCommissionByCourseTypeId( $courseTypeId);

    /**
     * get user commission discount details using commission discount id
     * @param $commissionDiscountId
     * @return mixed
     * @internal param $id
     */
    public function getUserCommissionDetails($commissionDiscountId);

    /**
     * Update user commission discount data using commission discount id
     * @param $commissionDataRecords
     * @param $commDiscountId
     * @return mixed
     */
    public function updateCommissionData($commissionDataRecords, $commDiscountId);

    /**
     * Check already inserted data is exist then update other wise insert
     * @param $commissionData
     * @param $checkDataKeys
     * @return mixed
     */
    public function createOrUpdateCommissionData($commissionData, $checkDataKeys);
}
