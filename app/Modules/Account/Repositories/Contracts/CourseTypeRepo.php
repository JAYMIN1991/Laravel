<?php

namespace App\Modules\Account\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CourseTypeRepo
 * @package namespace App\Modules\Account\Repositories\Contracts;
 * @see CourseTypeRepo
 */
interface CourseTypeRepo extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getCourseTypeList();
}
