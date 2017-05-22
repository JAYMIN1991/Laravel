<?php

namespace App\Modules\Location\Repositories\Criteria;

use Flinnt\Repository\Contracts\CriteriaInterface;
use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Class StateDefaultCountryCriteria
 * @package namespace App\Modules\Location\Criteria;
 */
class StateDefaultCountryCrit implements CriteriaInterface
{
    /**
     * Criteria for getting indian states
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('country_id',DEFAULT_COUNTRY_ID);
    }
}
