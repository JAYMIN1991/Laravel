<?php

namespace App\Modules\Sales\Repositories;

use App\Modules\Sales\Repositories\Criteria\AfterSalesVisitSearchCrit;
use DB;
use App;
use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\AfterSalesVisitRepo;
use App\Modules\Sales\Repositories\Criteria\NonDeletedCrit;
use Flinnt\Repository\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class AfterSalesVisit
 * @package namespace App\Modules\Sales\Repositories;
 */
class AfterSalesVisit extends BaseRepository implements AfterSalesVisitRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'after_sales_visit_id';

	/**
	 * Function to get table name
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_BACKOFFICE_AFTER_SALES_VISIT;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
		$this->pushCriteria(App::make(NonDeletedCrit::class));
	}

	/**
	 * Get Last After Sales Visit Details of Institute
	 *
	 * @param int   $instituteId Id of Institute
	 * @param array $columns     all, or Specific columns you want
	 *
	 * @return \Illuminate\Support\Collection Return collection object of last visit detail in provided institute
	 */
	public function getAfterSalesVisitDetailByInstituteId( $instituteId, $columns = [] ) {
		$this->limit(1);
		$this->orderBy('inserted', 'desc');
		return $this->getAfterSalesVisitDetail(null, ['inst_user_id' => $instituteId], $columns)->first();//->sortByDesc('after_sales_visit_id')->first();
	}


	/**
	 * Get Details of after sales visit
	 *
	 * @param int|array  $primaryKey PrimaryKey value
	 * @param null|array $where
	 * @param null|array $columns
	 *
	 * @return \Illuminate\Support\Collection Return Records based on conditions
	 */
	public function getAfterSalesVisitDetail( $primaryKey = null, array $where = [], $columns = [] ) {
		$results = null;
		$defaultColumns = [
			'after_sales_visit_id',
			'visit_date',
			'contact_person',
			'contact_person_desig',
			'contact_person_email_id',
		    'contact_person_phone',
		    'inst_user_id',
		    'remarks',
		    'is_deleted'
		];
		if ( ! empty($columns) ) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		/* apply where conditions */
		if ( ! empty($where) ) {
			$this->applyConditions($where);
		}

		/* apply column selection */
		$results = ($primaryKey) ? $this->find($primaryKey, $defaultColumns) : $this->get($defaultColumns);

		return $this->parserResult($results);
	}


	/**
	 * Search after sales visit entries. This method applies AfterSalesVisitSearchCrit criteria
	 *
	 * @param bool $paginate   output format can be 'paginator' or 'collection'. default is paginator
	 * @param int  $pageNo     number of the page
	 * @param int  $pageLength length of the page
	 *
	 * @return  LengthAwarePaginator|Collection
	 */
	public function searchAfterSalesVisit( $paginate = true, $pageNo = null, $pageLength = PAGINATION_RECORD_COUNT ) {
		$this->popCriteria(NonDeletedCrit::class);
		$this->pushCriteria(App::make(AfterSalesVisitSearchCrit::class));

		$this->from($this->model().' as asv')
			 ->select([
					'after_sales_visit_id',
			 	    DB::raw("DATE_FORMAT(FROM_UNIXTIME(asv.visit_date),'%d/%m/%Y') `visit_date`"),
				    DB::raw('u.user_school_name `institute_name`'),
				    DB::raw("(SELECT CONCAT(CONCAT_WS(' ', st.first_name, st.last_name), ' (', st.city_name , ')' ) 
				                    FROM ". TABLE_BACKOFFICE_SALES_TEAM ." st WHERE st.admin_user_id = asv.inserted_user )  `ref_by`"),
				    'asv.contact_person',
				    'asv.contact_person_desig',
				    'asv.contact_person_phone',
				    'asv.remarks',
			        DB::raw("IF(asv.contact_person_email_id<>'' AND asv.contact_person_email_id IS NOT NULL, asv.contact_person_email_id, 'N/A') `contact_person_email_id`")
				])
			 ->join(TABLE_USERS." AS u",'u.user_id', '=', 'asv.inst_user_id')
			 ->where('asv.is_deleted',0)
			 ->orderBy('asv.after_sales_visit_id', 'DESC');
		if ( $paginate ) {
			$result = $this->paginate($pageLength, ['u.user_school_name'], 'page', $pageNo);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);
	}


	/**
	 * Update after sales visit entry
	 *
	 * @param array $afterSalesVisitData Array of data to be updated
	 * @param int $afterSalesVisitId Id of after sales visit entry
	 *
	 * @return \stdClass|array|null returns updated after sales visit entry
	 */
	public function updateAfterSalesVisit( $afterSalesVisitData, $afterSalesVisitId ) {
		return $this->updateById($afterSalesVisitData, $afterSalesVisitId);
	}

	/**
	 * Create new after sales visit Entry
	 *
	 * @param array $afterSalesVisitData Array of data to create after sales visit
	 *
	 * @return \stdClass|array|null Returns newly created after sales visit entry
	 */
	public function createAfterSalesVisit( $afterSalesVisitData ) {
		return $this->create($afterSalesVisitData);
	}

	/**
	 * Delete after sales visit entry
	 *
	 * @param $afterSalesVisitId
	 *
	 * @return \stdClass|array|null Returns softly deleted after sales visit entry
	 */
	public function deleteAfterSalesVisit( $afterSalesVisitId ) {
		return $this->updateById(['is_deleted' => 1], $afterSalesVisitId);
	}

	/**
	 * Get Available Designation
	 *
	 * @param string $term search term
	 *
	 * @return Collection
	 */
	public function getAvailableDesignations( $term = '' ) {
		$designations = $this->distinct()->select([DB::raw('TRIM(contact_person_desig) `value`')]);
		if ( ! empty($term) ) {
			$designations->where('contact_person_desig', 'LIKE', '%' . GeneralHelpers::clearParam($term, PARAM_RAW_TRIMMED) . '%');
		}
		$result = $designations->orderBy('value')->get();

		return $this->parserResult($result);
	}
}
