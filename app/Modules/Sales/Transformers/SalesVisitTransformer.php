<?php

namespace App\Modules\Sales\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Support\Collection;

/**
 * Class SalesVisitTransformer
 * @package namespace App\Modules\Sales\Transformers;
 */
class SalesVisitTransformer extends TransformerAbstract
{

	/**
	 * Transform the \SalesVisit entity
	 * @param Collection|array $model
	 *
	 * @return array
	 */
    public function transform($model)
    {
	    return [
		    'sales_visit_id' =>$model['sales_visit_id'],
		    'visit_date' => $model['visit_date'],
		    'contact_person' => $model['contact_person'],
		    'contact_person_desig' => $model['contact_person_design'],
		    'contact_person_email_id' => $model['contact_person_email_id'],
		    'contact_person_phone' => $model['contact_person_phone'],
		    'inst_inquiry_id' => $model['inst_inquiry_id'],
		    'acq_status' => $model['acq_status'],
		    'remarks' => $model['remarks'],
		    'is_deleted' => $model['is_deleted'],
		    'auto_acq' => $model['auto_acq_dt'],
		    'auto_acq_dt' => $model['auto_acq_dt'],
		    'inst_list_acq' => $model['inst_list_acq'],
		    'inst_list_acq_dt' => $model['inst_list_acq_dt'],
		    'member_id' => $model['member_id'],
		    'inserted' => $model['inserted'],
		    'inserted_user' => $model['inserted_user'],
		    'updated' => $model['updated'],
		    'updated_user' => $model['updated_user'],
		    'user_ip' => $model['user_ip'],
		    'device_type' => $model['device_type']
	    ];
    }
}
