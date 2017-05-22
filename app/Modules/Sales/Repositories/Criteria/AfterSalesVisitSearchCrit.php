<?php

namespace App\Modules\Sales\Repositories\Criteria;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Helper;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Session;

/**
 * Class AfterSalesVisitSearchCrit
 * @package namespace App\Modules\Sales\Repositories\Criteria;
 */
class AfterSalesVisitSearchCrit extends AbstractCriteria {

	/**
	 * @var Request
	 */
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
	 * @return Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		/* apply institute filter */
		if ( $this->request->has('institute') ) {
			$instituteId = GeneralHelpers::decode($this->request->input('institute'));
			$model->where('u.user_id',$instituteId);
		}

		/* apply visit_by filter*/
		if ( $this->request->has('visit_by') ) {
			$model->whereIn('asv.inserted_user', [GeneralHelpers::clearParam($this->request->input('visit_by'), PARAM_RAW_TRIMMED)]);
		} else {
			/* apply default visit_by filter as any one from permitted visitor list */
			$visitBy = App::make(SalesTeamRepo::class)->getList(Session::get('user_id'))->keys()->toArray();
			$model->whereIn('asv.inserted_user', $visitBy);
		}

		/* Apply Date range filter */
		if ( ! empty($this->request->input('visit_date_from')) && ! empty($this->request->input('visit_date_to')) ) {

			/* Both date_from and date_to are supplied but not empty */
			$dateFrom = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_from'), PARAM_RAW_TRIMMED));
			$dateTo = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_to'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(asv.visit_date)) BETWEEN ? AND ?", [$dateFrom, $dateTo]);

		} elseif ( empty($this->request->input('visit_date_from')) && ! empty($this->request->input('visit_date_to')) ) {

			/* Only date_to is supplied */
			$dateTo = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_to'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(asv.visit_date)) BETWEEN ? AND ? ", ["0000-00-00", $dateTo]);

		} elseif ( ! empty($this->request->input('visit_date_from')) && empty($this->request->input('visit_date_to')) ) {

			/* Only date_from is supplied */
			$dateFrom = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_from'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(asv.visit_date)) BETWEEN ? AND ?", [
				$dateFrom,
				(string) Helper::getDate(trans('shared::config.mysql_date_format'))
			]);

		} elseif ( ! $this->request->exists('visit_date_from') && ! $this->request->exists('visit_date_to') ) {

			/* When First time page is loaded without any date supplied */
			$model->whereRaw("DATE(FROM_UNIXTIME(asv.visit_date)) BETWEEN ? AND ?", [
				(string) Helper::getDate(trans('shared::config.mysql_date_format')),
				(string) Helper::getDate(trans('shared::config.mysql_date_format'))
			]);
		}

		return $model;
	}
}
