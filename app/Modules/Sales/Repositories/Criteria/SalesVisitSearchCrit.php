<?php

namespace App\Modules\Sales\Repositories\Criteria;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\SalesTeamRepo;
use App\Modules\Shared\Misc\ViewHelper;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Helper;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Session;

/**
 * Class InstCallVisitCriteria
 * @package namespace App\Modules\Sales\Criteria;
 */
class SalesVisitSearchCrit extends AbstractCriteria {

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
	 * @param Builder             $model      Instance of Builder class
	 * @param RepositoryInterface $repository Instance of RepositoryInterface
	 *
	 * @return Builder Returns Builder instance after applying all catteries
	 */
	public function apply( $model, RepositoryInterface $repository ) {

		$inquiryConverted = $this->request->input('inquiry_converted');

		if ( $inquiryConverted == ViewHelper::SELECT_OPTION_VALUE_NO ) {

			$model->where('ii.acq_status', ViewHelper::SELECT_OPTION_VALUE_NO - 1);
		} else if ( $inquiryConverted == ViewHelper::SELECT_OPTION_VALUE_YES ) {

			/* Yes option is selected */
			$model->where('ii.acq_status', ViewHelper::SELECT_OPTION_VALUE_YES - 1);
		}

		/* Apply category filter */
		if ( $this->request->has('category') ) {
			$model->where('ii.inst_category_id', GeneralHelpers::clearParam($this->request->input('category'), PARAM_RAW_TRIMMED));
		}

		/* Apply institute filter */
		if ( $this->request->has('institute') ) {
			$model->where('ii.inst_inquiry_id', GeneralHelpers::clearParam($this->request->input('institute'), PARAM_RAW_TRIMMED));
		}

		/* Apply visit_by filter*/
		if ( $this->request->has('visit_by') ) {
			$model->whereIn('sv.inserted_user', [GeneralHelpers::clearParam($this->request->input('visit_by'), PARAM_RAW_TRIMMED)]);
		} else {
			/* Apply default visit_by filter as any one from permitted visitor list */
			$visitBy = App::make(SalesTeamRepo::class)->getList(Session::get('user_id'))->keys()->toArray();
			if(count($visitBy)>0)
			{
				$model->whereIn('sv.inserted_user', $visitBy);
			}
		}

		if ( ! empty($this->request->input('visit_date_from')) && ! empty($this->request->input('visit_date_to')) ) {

			/* Both date_from and date_to are supplied but not empty */
			$dateFrom = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_from'), PARAM_RAW_TRIMMED));
			$dateTo = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_to'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(sv.visit_date)) BETWEEN ? AND ?", [$dateFrom, $dateTo]);

		} elseif ( empty($this->request->input('visit_date_from')) && ! empty($this->request->input('visit_date_to')) ) {

			/* Only date_to is supplied */
			$dateTo = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_to'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(sv.visit_date)) BETWEEN ? AND ? ", ["0000-00-00", $dateTo]);

		} elseif ( ! empty($this->request->input('visit_date_from')) && empty($this->request->input('visit_date_to')) ) {

			/* Only date_from is supplied */
			$dateFrom = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($this->request->input('visit_date_from'), PARAM_RAW_TRIMMED));
			$model->whereRaw("DATE(FROM_UNIXTIME(sv.visit_date)) BETWEEN ? AND ?", [
				$dateFrom,
				(string) Helper::getDate(trans('shared::config.mysql_date_format'))
			]);

		} elseif ( ! $this->request->exists('visit_date_from') && ! $this->request->exists('visit_date_to') ) {

			/* When First time page is loaded without any date supplied */
			$model->whereRaw("DATE(FROM_UNIXTIME(sv.visit_date)) BETWEEN ? AND ?", [
				(string) Helper::getDate(trans('shared::config.mysql_date_format')),
				(string) Helper::getDate(trans('shared::config.mysql_date_format'))
			]);

		}

		return $model;
	}
}
