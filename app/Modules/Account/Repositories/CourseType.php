<?php

namespace App\Modules\Account\Repositories;

use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Account\Repositories\Contracts\CourseTypeRepo;


/**
 * Get course type list and by course type id
 * Class CourseType
 * @package namespace App\Modules\Account\Repositories;
 * @see CourseType
 */
class CourseType extends BaseRepository implements CourseTypeRepo
{
   /**
   	 * Primary Key
   	 * @var String
   	 */
   	protected $primaryKey = 'course_type_id';


    /**
     * Specify Table name
     *
     * @return string
     */
    public function model()
    {
        return TABLE_COURSE_TYPES;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        //$this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return mixed
     */
    public function getCourseTypeList(){
        $results = $this->where('is_public','=','1')->pluck('course_type', 'course_type_id');

        return $this->parserResult($results);
    }
}
