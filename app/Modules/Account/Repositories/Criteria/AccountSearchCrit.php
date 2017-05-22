<?php

namespace App\Modules\Account\Repositories\Criteria;

use App;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class AccountSearchCrit
 * @package namespace App\Modules\Account\Repositories\Criteria;
 * @see AccountSearchCrit
 */
class AccountSearchCrit extends AbstractCriteria
{
    protected $request;
    /**
     * RequestCriteria constructor
     *
     * @param Request $request
     */
    public function __construct( Request $request ) {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model;
    }
}
