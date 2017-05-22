<?php

namespace App\Modules\Account\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface PayCommissionsRepo
 * @package namespace App\Modules\Account\Repositories\Contracts;
 * @see PayCommissionsRepo
 */
interface PayCommissionsRepo extends RepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getCourseTypeByCommissionId($id);
}
