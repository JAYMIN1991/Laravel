<?php

namespace App\Modules\Account\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface InstCategoryRepo
 * @package namespace App\Modules\Account\Repositories\Contracts;
 * @see InstituteBankRepo
 */
interface InstituteBankRepo extends RepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getBankAndInvoiceByInstituteId( $id );
}
