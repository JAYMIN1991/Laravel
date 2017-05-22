<?php

namespace App\Modules\Publisher\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Helper;
use Illuminate\Http\Request;

/**
 * Class CambridgeTKTExamSearchCrit
 * @package namespace App\Modules\Publisher\Repositories\Criteria;
 * @see     CambridgeTKTExamSearchCrit
 */
class CambridgeTKTExamSearchCrit extends AbstractCriteria {

	protected $request;

	/**
	 * CambridgeTKTExamSearchCrit constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * @param                                                  $model
	 * @param RepositoryInterface                              $repository
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		/* @var \Illuminate\Database\Query\Builder $model */
		$moduleList = GeneralHelpers::clearParam($this->request->input('module_list_id'), PARAM_RAW_TRIMMED);
		$date = GeneralHelpers::clearParam($this->request->input('date'), PARAM_RAW_TRIMMED);
		$city_name = GeneralHelpers::clearParam($this->request->input('city_name'), PARAM_RAW_TRIMMED);
		$url = GeneralHelpers::clearParam($this->request->input('url'), PARAM_RAW_TRIMMED);

		if ( ! empty($moduleList) ) {
			$model->where('test_name', '=', $moduleList);
		}

		if ( $this->request->has('date') ) {
			$date = GeneralHelpers::saveFormattedDate($date);
			$date = Helper::timestempToDate(strtotime($date))->format('d M Y');
			$model->where('test_date', '=', $date);
		}

		if ( $this->request->has('city_name') && $city_name != '0' ) {
			$model->where('test_location', GeneralHelpers::clearParam($city_name, PARAM_RAW_TRIMMED));
		}

		if ( $this->request->has('url') ) {
			$model->where('test_url', GeneralHelpers::clearParam($url, PARAM_RAW_TRIMMED));
		}

		return $model;
	}
}
