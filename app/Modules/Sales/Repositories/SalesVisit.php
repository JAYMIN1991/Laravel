<?php

namespace App\Modules\Sales\Repositories;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\SalesVisitRepo;
use App\Modules\Sales\Repositories\Criteria\SalesVisitSearchCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class SalesVisitRepositoryEloquent
 * @package namespace App\Modules\Sales\Repositories;
 */
class SalesVisit extends BaseRepository implements SalesVisitRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'sales_visit_id';

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get Available Designation
	 *
	 * @param string $term search term
	 *
	 * @return Collection|null Returns collection of designation based on search term
	 */
	public function getAvailableDesignations( $term = '' ) {
		$designations = $this->distinct()->select([DB::raw('TRIM(contact_person_desig) `value`')]);
		if ( ! empty($term) ) {
			$designations->where('contact_person_desig', 'LIKE', '%' . GeneralHelpers::clearParam($term, PARAM_RAW_TRIMMED) . '%');
		}
		$result = $designations->orderBy('value')->get();

		return $this->parserResult($result);
	}

	/**
	 * Returns list of sales visit by applying search criteria
	 *
	 * @param bool $paginate   True means paginate the output and false means output as collection
	 * @param int  $pageNo     Number of the page
	 * @param int  $pageLength Length of the page
	 *
	 * @return  LengthAwarePaginator|Collection Returns Pagination instance or collection after applying SalesVisitSearchCrit
	 */
	public function search( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT ) {
		/* NonDeletedCriteria Handled internally */
		$this->pushCriteria(App::make(SalesVisitSearchCrit::class));

		$this->from($this->model() . " as sv")
		     ->select([
			     'sv.sales_visit_id',
			     DB::raw('DATE_FORMAT(FROM_UNIXTIME(sv.visit_date),\'%d/%m/%Y\') `visit_date`'),
			     'ii.institute_name',
			     'ii.address',
			     'ii.city',
			     DB::raw('(SELECT st.state_name' . ' FROM ' . TABLE_STATES . ' st' . ' WHERE st.state_id = ii.state_id) `state`'),
			     DB::raw('(SELECT ic.category_name' . ' FROM ' . TABLE_BACKOFFICE_INST_CATEGORY . ' ic' . ' WHERE ic.category_id = ii.inst_category_id' . ' AND ic.category_active = ' . CATEGORY_ACTIVE . ' ) `category_name`'),
			     DB::raw("(
                                SELECT 
                                        CONCAT(
	                                        CONCAT_WS(' ', st.first_name, st.last_name), 
	                                        ' (', 
	                                            st.city_name , 
	                                        ')' 
                                        )" . " FROM " . TABLE_BACKOFFICE_SALES_TEAM . " st" . " WHERE st.admin_user_id = sv.inserted_user )  `ref_by`"),
			     'sv.contact_person',
			     'sv.contact_person_desig',
			     'sv.contact_person_phone',
			     'sv.remarks',
			     'ii.acq_status',
			     DB::raw('sv.acq_status `visit_acq_status`'),
			     DB::raw("CASE WHEN sv.acq_status = 1 THEN 'Yes' ELSE 'No' END `acq_status_label`"),
		         DB::raw("IF(sv.contact_person_email_id <> '' AND sv.contact_person_email_id IS NOT NULL, sv.contact_person_email_id, 'N/A') `contact_person_email_id`")
		     ])
		     ->join(TABLE_BACKOFFICE_INST_INQUIRY . " as ii", "sv.inst_inquiry_id", "=", "ii.inst_inquiry_id")
		     ->where('sv.is_deleted', '=', 0)/* nonDeletedCriteria */
		     ->where(function ( $query ) {
				$query->whereNull('ii.inst_list_acq')->orWhere('ii.inst_list_acq', '=', 0);
			})
		     ->orderBy("sv.sales_visit_id",'desc');
		$result = $paginate ? $this->paginate($pageLength, ['*'], $pageNo) : $this->get();

		return $this->parserResult($result);
	}

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_BACKOFFICE_SALES_VISIT;
	}

	/**
	 * Getting detail of sales visit
	 *
	 * @param int   $salesVisitId Id of sales visit
	 * @param array $where        array of where conditions
	 * @param array $columns      columns to fetch in result
	 *
	 * @return array|null Returns array of sales visit, null if not found
	 */
	public function getDetail( $salesVisitId, array $where = [], $columns = [] ) {
		$defaultColumns = [
			'sales_visit_id',
		    'visit_date',
		    'contact_person',
		    'contact_person_desig',
		    'contact_person_email_id',
		    'contact_person_phone',
		    'inst_inquiry_id',
		    'acq_status',
		    'remarks',
		    'is_deleted',
		    'member_id'
		];

		/* Merge the columns if provided in parameter */
		if ( ! empty($defaultColumns) ) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		/* Fetch sales visit or multiple visits with custom where clause */
		if ( ! GeneralHelpers::isNull($salesVisitId) ) {
			$results = $this->where('sales_visit_id', $salesVisitId)->first();
		} else {
			$this->applyConditions($where);
			$results = $this->get($defaultColumns);
		}

		return $this->parserResult($results);
	}

	/**
	 * Create new sales visit
	 *
	 * @param array $data Array of data to create sales visit
	 *
	 * @return \stdClass|array|null Returns newly created sales visit entry
	 */
	public function createSalesVisit( $data ) {
		return $this->create($data);
	}

	/**
	 * Get Sales Visit details with Institute and Category
	 *
	 * @param int $salesVisitId Id for sales visit
	 *
	 * @return array|null Returns detail of institute or null if not found
	 */
	public function getInstituteAndCategoryDetail( $salesVisitId ) {
		$results = $this->from($this->model() . " as sv")
		                ->select([
			                'ii.inst_inquiry_id',
			                'ii.institute_name',
			                'ii.address',
			                'ii.city',
			                'ii.converted_inst_id',
			                'ii.acq_status',
			                DB::raw('(SELECT st.state_name FROM ' . TABLE_STATES . ' st WHERE st.state_id = ii.state_id ) state '),
			                DB::raw('(SELECT ic.category_name' . '         FROM ' . TABLE_BACKOFFICE_INST_CATEGORY . ' ic' . '         WHERE' . '         ic.category_id = ii.inst_category_id' . '         AND' . '         ic.category_active = ' . CATEGORY_ACTIVE . ') category_name'),
			                'sv.contact_person',
			                'sv.contact_person_desig',
			                'sv.contact_person_phone',
			                'sv.remarks',
			                'sv.sales_visit_id',
			                'sv.updated_user',
			                'sv.acq_status as visit_acq_status'
		                ])
		                ->join(TABLE_BACKOFFICE_INST_INQUIRY . ' as ii', 'sv.inst_inquiry_id', '=', 'ii.inst_inquiry_id')
		                ->where('sv.sales_visit_id', '=', $salesVisitId)
		                ->where('is_deleted', 0)
		                ->first();

		return $this->parserResult($results);
	}

	/**
	 * check salesVisitId and inquiryId combination
	 *
	 * @param int $salesVisitId Id of sales visit
	 * @param int $instInquiryId Id of institute Inquiry
	 *
	 * @return bool Returns true if instInquiryId is mapped with salesVisitId
	 */
	public function checkInquiryIdVisitIdCombination( $salesVisitId, $instInquiryId ) {

		$result = $this->where([
			['sales_visit_id', '=', $salesVisitId],
			['inst_inquiry_id', '=', $instInquiryId]
		])->get();

		return (!empty($result)) ? true : false;
	}

	/**
	 * Update acquisition details of sales visit
	 *
	 * @param int  $salesVisitId      Id of the sales visit
	 * @param int  $userId            Id of the user who is removing acquisition
	 * @param bool $acquisitionStatus Status of the acquisition
	 *
	 * @return bool Status of the acquisition operation
	 */
	public function updateAcquisition( $salesVisitId, $userId, $acquisitionStatus ) {
		$acq_status = $acquisitionStatus ? 1 : 0;

		$visitData = [
			'acq_status'   => $acq_status,
			'user_ip'      => Helper::getIPAddress(true),
			'updated'      => Helper::datetimeToTimestamp(),
			'updated_user' => (int) $userId
		];

		$visitStatus = $this->updateSalesVisit($visitData, $salesVisitId);

		return ($visitStatus) ? true : false;
	}

	/**
	 * Update Visit Entry
	 *
	 * @param array $data         Array of data to be updated
	 * @param int   $salesVisitId Id of sales visit entry
	 * @param array $where        Array of condition
	 *
	 * @return array|null|\stdClass|int Return updated sales visit entry
	 */
	public function updateSalesVisit( $data, $salesVisitId = null, array $where = [] ) {

		if ( ! is_null($salesVisitId) ) {
			return $this->updateById($data, $salesVisitId);
		} else {
			$this->applyConditions($where);

			return $this->update($data);
		}
	}

	/**
	 *  This function gives statistic for acquisition report
	 *
	 * @param array $filterData array of filters
	 *                          $filterData['ref_by'] array array of sales member_id
	 *                          $filterData['date_from'] date date in format of input date format (d/m/Y)
	 *                          $filterData['date_to'] date date in format of input date format (d/m/Y)
	 *                          $filterData['date_range_on'] int field on which date range should be applied. it can be institute or user. @see App\Modules\Shared\Misc\AcquisitionReportViewHelper
	 *                          $filterData['post_type'] int operator for post count. option can be 'greater then', 'less then', or 'equals to'. @see App\Modules\Shared\Misc\AcquisitionReportViewHelper
	 *                          $filterData['post_value'] int count for total post
	 *                          $filterData['course_user_id'] int user_id of institute
	 * @param bool  $paginate   output format can be 'paginator' or 'collection'. default is paginator
	 * @param int   $pageNo     number of the page
	 * @param int   $pageLength length of the page
	 *
	 * @return  LengthAwarePaginator|Collection
	 * @see App\Modules\Sales\Http\Requests\Visit\AcquisitionRequest @ report
	 */
	public function searchAcquisition( $filterData, $paginate = true, $pageNo = null,
	                                   $pageLength = PAGINATION_RECORD_COUNT ) {

		$fromDate = "0000-00-00";
		$toDate = "0000-00-00";
		$whereRaw = " DATE(FROM_UNIXTIME(?)) BETWEEN  '?' and '?' ";
		$refBy = [-1];
		$courseUserId = 0;
		$havingRaw = '';


		if ( ! empty($filterData['ref_by']) && is_array($filterData['ref_by']) ) {
			$refBy = $filterData['ref_by'];
		}
		if ( ! empty($filterData['course_user_id']) && (int) $filterData['course_user_id'] > 0 ) {
			$courseUserId = GeneralHelpers::clearParam($filterData['course_user_id'], PARAM_INT);
		}

		if ( ! empty($filterData['date_from']) && ! GeneralHelpers::isNull($filterData['date_from']) && empty($filterData['date_to']) ) {

			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($filterData['date_from'], PARAM_RAW_TRIMMED));
			$toDate = (string) Helper::getDate(trans('shared::config.mysql_date_format'));
		} else if ( ! empty($filterData['date_to']) && ! GeneralHelpers::isNull($filterData['date_to']) && empty($filterData['date_from']) ) {

			/* fromDate  ='0000-00-00'  */
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($filterData['date_to'], PARAM_RAW_TRIMMED));
		} else if ( ! empty($filterData['date_from']) && ! GeneralHelpers::isNull($filterData['date_from']) && ! empty($filterData['date_to']) && ! GeneralHelpers::isNull($filterData['date_to']) ) {

			$fromDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($filterData['date_from'], PARAM_RAW_TRIMMED));
			$toDate = GeneralHelpers::saveFormattedDate(GeneralHelpers::clearParam($filterData['date_to'], PARAM_RAW_TRIMMED));
		}

		if ( ! empty($filterData['date_range_on']) && (int) $filterData['date_range_on'] == 1 ) {
			$havingRaw = " total_users > 0";

		} else {
			$havingRaw = " x.user_term_dt IS NOT NULL ";
			if ( ! ($fromDate == '0000-00-00' && $toDate == "0000-00-00") ) {
				$havingRaw .= 'AND ' . str_replace_array('?', ['x.user_term_dt', $fromDate, $toDate], $whereRaw);
			}
		}

		if ( ! empty($filterData['post_type']) && (int) $filterData['post_type'] > 0 ) {
			$postFilter = (int) ($filterData['post_type']);
			$postFilterValue = (int) ($filterData['post_value']);
			$postFilterOperator = '';

			if ( $postFilter == 1 ) {
				$postFilterOperator = ' > ';
			} else if ( $postFilter == 2 ) {
				$postFilterOperator = ' < ';
			} else if ( $postFilter == 3 ) {
				$postFilterOperator = ' = ';
			}
			$havingRaw .= ' AND (total_posts ' . $postFilterOperator . $postFilterValue . ')';
		}

		$subQuery = DB::table(TABLE_USERS . ' as o')
		              ->select([
			              'o.user_id AS owner_id',
			              'o.user_school_name AS institution',
			              'o.user_term_dt',
			              'u.user_id',
			              DB::raw('IF(su.user_id IS NOT NULL, 1, 0) AS totalsearch'),
			              DB::raw('IF(u.user_acc_verified = 1, 1, 0) AS user_acc_verified'),
			              DB::raw('IF(d.user_id IS NOT NULL, 1, 0) AS mobile_user_id'),
			              'st.first_name'
		              ])
		              ->join(TABLE_BACKOFFICE_INST_INQUIRY . ' as ii', 'o.user_id', '=', 'ii.converted_inst_id')
		              ->join(TABLE_BACKOFFICE_SALES_TEAM . ' as st', 'ii.acq_member_id', '=', 'st.member_id')
		              ->join(TABLE_COURSES . ' as c', 'o.user_id', '=', 'c.course_owner')
		              ->join(TABLE_USER_COURSES . ' as uc', function ( $join ) {

			              /* @var JoinClause $join */
			              $join->on(function ( $query ) {

				              /* @var Builder $query */
				              $query->whereColumn('uc.user_mod_course_id', '=', 'c.course_id')
				                    ->where('uc.user_mod_expired', '=', 0)
				                    ->where('uc.user_mod_is_active', '=', 1)
				                    ->where('uc.user_mod_role_id', '=', 3);
			              });
		              })
		              ->join(TABLE_USERS . ' as su', function ( $join ) {

			              /* @var JoinClause $join */
			              $join->on(function ( $query ) {

				              /* @var Builder $query */
				              $query->whereColumn('su.user_id', '=', 'uc.user_mod_user_id')
				                    ->whereNotNull('su.user_term_dt');
			              });
		              })
		              ->leftJoin(TABLE_USERS . ' as u', function ( $join ) use ( $whereRaw, $fromDate, $toDate ,$filterData) {

			              /* @var JoinClause $join */
			              $join->on(function ( $query ) use ( $whereRaw, $fromDate, $toDate, $filterData ) {

				              /* @var Builder $query */
				              $query->whereColumn('uc.user_mod_user_id', '=', 'u.user_id')
				                    ->whereNotNull('u.user_term_dt');

							  /* This condition is applied when date range is applied on institute,
							     confusing but don't apply when range applied on user to make same result like backoffice  */
				              if ( (! ($fromDate == '0000-00-00' && $toDate == '0000-00-00')) &&  $filterData['date_range_on'] == 1) {
				              	  //$query->whereRaw($whereRaw, ['u.user_term_dt', $fromDate, $toDate]);
					              $query->whereRaw(str_replace_array('?', [
						              'u.user_term_dt',
						              $fromDate,
						              $toDate
					              ], $whereRaw));
				              }
			              });
		              })
		              ->leftJoin(TABLE_DEVICE_REGISTRATIONS . ' as d', 'd.user_id', '=', 'u.user_id')
		              ->whereRaw('IFNULL(o.user_plan_id, 0) > 0')
		              ->whereRaw("IFNULL(o.user_school_name, '') <> ''")
		              ->whereRaw("IF(0 = 0, 1 = 1, o.user_plan_id = 0)")
		              ->whereRaw('(o.user_acc_closed = 0 OR o.user_acc_closed IS NULL)')
		              ->where('su.user_is_active', '=', 1)
		              ->where('o.user_is_active', '=', 1)
		              ->where('c.course_enabled', '=', 1)
		              ->where('c.course_status', '=', COURSE_STATUS_PUBLISH)
		              ->whereIn('ii.acq_member_id', $refBy);

		if ( $courseUserId > 0 ) {
			$subQuery->where('o.user_id', $courseUserId);
		}

		$subQuery->groupBy(['o.user_id', 'o.user_school_name', 'o.user_term_dt', 'su.user_id', 'st.first_name']);

		$mainQuery = DB::table(TABLE_USERS)->select([
				'x.first_name as ref_by',
				'x.owner_id',
				'x.institution As institute_name',
				DB::raw('IFNULL(COUNT(x.user_id), 0) AS total_users'),
				DB::raw('IFNULL(SUM(x.totalsearch), 0) AS total_search_user'),
				DB::raw('IFNULL(SUM(user_acc_verified), 0) AS total_verified'),
				DB::raw('IFNULL(SUM(mobile_user_id), 0) AS total_mobile'),
				DB::raw("IFNULL( 
											 (SELECT 
									                COUNT(DISTINCT p.id) AS pCnt 
									          FROM " . TABLE_COURSES . " c INNER JOIN " . TABLE_POST . " p 
										      ON (
										            p.module_id = c.course_id AND 
										            p.publish_date IS NOT NULL" . ((! ($fromDate == '0000-00-00' && $toDate == '0000-00-00')) ? " AND " . str_replace_array('?', [
							'p.publish_date',
							$fromDate,
							$toDate
						], $whereRaw) : "") . "
												 )
									          WHERE 
									                c.course_enabled = 1 AND 
									                c.course_status = 2 AND 
									                c.course_owner = x.owner_id
									          GROUP BY  c.course_owner)
									              , 0) 
									         AS total_posts ")
			])->from(DB::raw('(' . $subQuery->toSql() . ') as x'))->mergeBindings($subQuery)->groupBy([
				'x.owner_id',
				'x.user_term_dt',
				'x.first_name'
			]);

		if ( $havingRaw != '' ) {
			$mainQuery->havingRaw($havingRaw);
		}

		$mainQuery->orderBy('x.first_name')->orderBy('x.institution');

		$mainWrapperQuery = DB::table(TABLE_USERS)
		                      ->selectRaw('*')
		                      ->from(DB::raw('(' . $mainQuery->toSql() . ') as xx'))
		                      ->mergeBindings($mainQuery);

		if ( $paginate ) {
			$result = $mainWrapperQuery->paginate($pageLength, ['xx.owner_id'], 'page', $pageNo);
		} else {
			$result = $mainWrapperQuery->get();
		}

		return $this->parserResult($result);
	}
}