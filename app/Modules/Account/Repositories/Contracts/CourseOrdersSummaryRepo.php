<?php

namespace App\Modules\Account\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CourseOrdersSummaryRepo
 * @package namespace App\Modules\Account\Repositories\Contracts;
 * @see CourseOrdersSummaryRepo
 */
interface CourseOrdersSummaryRepo extends RepositoryInterface
{
    /*
     * Get Course order summary search result
     * */
    public function getCourseOrderSummaryResult($pagination = null);
}
