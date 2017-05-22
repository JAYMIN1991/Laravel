<?php

namespace App\Modules\Account\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Account\Repositories\Contracts\PayCommissionsRepo;

/**
 *
 * Class PayCommissions
 * @package namespace App\Modules\Account\Repositories;
 * @see PayCommissions
 */
class PayCommissions extends BaseRepository implements PayCommissionsRepo
{

   /**
   	 * Primary Key
   	 * @var String
   	 */
   	protected $primaryKey = 'commission_id';


    /**
     * Specify TableName
     *
     * @return string
     */
    public function model()
    {
        return TABLE_PAY_COMMISSIONS;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        //$this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Get course type id from commission id
     * @param $commissionId
     * @return mixed
     * @internal param $id
     */
    public function getCourseTypeByCommissionId($commissionId) {
        $results = $this->from(TABLE_COURSE_TYPES)
                    ->select([TABLE_COURSE_TYPES.'.course_type_id'])
                    ->join($this->model(),$this->model().'.course_type','=',TABLE_COURSE_TYPES.'.course_type_id')
                    ->where($this->model().'.commission_id','=',$commissionId)->first();

        return $this->parserResult($results);
    }
}
