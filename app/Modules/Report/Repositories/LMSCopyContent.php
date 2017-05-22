<?php

namespace App\Modules\Report\Repositories;

use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Report\Repositories\Contracts\LMSCopyContentRepo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class LMSCopyContent
 * @package namespace App\Modules\Report\Repositories;
 */
class LMSCopyContent extends BaseRepository implements LMSCopyContentRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'job_id';


	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_LMS_COPY_CONTENT;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get the report (views and comments) of copied courses
	 *
	 * @param bool $showDeleted True will show deleted course
	 * @param bool $paginate    True will return LengthAwarePaginator
	 *
	 * @return \Illuminate\Pagination\LengthAwarePaginator|mixed
	 */
	public function getContentUserReport( $showDeleted = false, $paginate = true ) {
		DB::statement("SET GROUP_CONCAT_MAX_LEN = 10000000");

		$this->distinct()
		     ->select([
			     'fu.user_id as source_inst',
			     'fc.course_id as source_course_id',
			     'tc.course_id as target_course_id',
			     'tu.user_id as target_inst',
			     'tu.user_school_name as target_inst_name',
			     'fc.course_name as source_course',
			     'tc.course_name as target_course',
			     DB::raw('SUM(IFNULL(' . TABLE_LMS_SECTION_STATS . '.stat_views,0)) as views'),
			     DB::raw('SUM(IFNULL(' . TABLE_LMS_SECTION_STATS . '.stat_comments,0)) as comments')
		     ])
		     ->join(TABLE_COURSES . ' as fc', 'fc.course_id', '=', TABLE_LMS_COPY_CONTENT . '.copy_from_course_id')
		     ->join(TABLE_USERS . ' as fu', 'fu.user_id', '=', 'fc.course_owner')
		     ->join(TABLE_COURSES . ' as tc', 'tc.course_id', '=', TABLE_LMS_COPY_CONTENT . '.copy_to_course_id')
		     ->join(TABLE_USERS . ' as tu', 'tu.user_id', '=', 'tc.course_owner')
		     ->join(TABLE_LMS_SECTIONS, function ( $join ) {
			     /** @var JoinClause $join */
			     $join->on(TABLE_LMS_SECTIONS . '.course_id', '=', TABLE_LMS_COPY_CONTENT . '.copy_to_course_id')
			          ->on(TABLE_LMS_SECTIONS . '.section_copy_course', '=',
				          TABLE_LMS_COPY_CONTENT . '.copy_from_course_id');
		     })
		     ->join(TABLE_LMS_SECTION_STATS, TABLE_LMS_SECTION_STATS . '.stat_section_id', '=',
			     TABLE_LMS_SECTIONS . '.section_id');

		if ( ! $showDeleted ) {
			$this->where('fc.course_status', '=', COURSE_STATUS_PUBLISH);
			$this->where('fc.course_enabled', '=', 1);
			$this->where('tc.course_status', '=', COURSE_STATUS_PUBLISH);
			$this->where('tc.course_enabled', '=', 1);
		}

		$this->groupBy('fu.user_id', 'tu.user_id', 'tu.user_school_name',
			TABLE_LMS_COPY_CONTENT . '.copy_from_course_id', TABLE_LMS_COPY_CONTENT . '.copy_to_course_id');
		$this->orderBy('views', 'DESC');

		$perPage = config('repository.pagination.limit', 15);
		$currentPage = Paginator::resolveCurrentPage();

		// If paginate is true create the paginate
		if ( $paginate ) {
			$paginateResult = DB::select(DB::raw('select count(*) as count from (' . $this->toSql() . ') as x'));
			$total = $paginateResult[0]['count'];
			$data = $this->forPage($currentPage, $perPage)->get();

			return new LengthAwarePaginator($data->all(), $total, $perPage, $currentPage, [
				'path'     => Paginator::resolveCurrentPath(),
				'pageName' => 'page',
			]);
		} else {
			$result = $this->get();
		}

		return $this->parserResult($result);

	}
}
