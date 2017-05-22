<?php
namespace App\Modules\Sales\Repositories;

use App\Common\GeneralHelpers;
use App\Modules\Sales\Repositories\Contracts\InstInquiryRepo;
use App\Modules\Sales\Repositories\Criteria\InstituteNotAcquiredCrit;
use App\Modules\Sales\Repositories\Criteria\NonInstListAcqCrit;
use DB;
use Flinnt\Repository\Eloquent\BaseRepository;
use Helper;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class InstInquiryRepositoryEloquent
 * @package namespace App\Modules\Sales\Repositories;
 */
class InstInquiry extends BaseRepository implements InstInquiryRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'inst_inquiry_id';

	/**
	 * Function to get table name
	 *
	 * @return string table name
	 */
	public function model() {
		return TABLE_BACKOFFICE_INST_INQUIRY;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Get non acquired institute list
	 *
	 * @return Collection Returns Collection to fill in drop-down
	 */
	public function getListForNonAcquiredInstInquiry() {
		$this->pushCriteria(InstituteNotAcquiredCrit::class);
		return $this->getList();
	}

	/**
	 * Get institute list which are not created via old institute list page
	 *
	 * @return Collection Returns Collection to fill in drop-down
	 */
	public function getListOfInstituteNotAcquiredFromInstituteList() {
		$this->pushCriteria(NonInstListAcqCrit::class);
		return $this->getList();
	}

	/**
	 * Get list of  All Institute
	 *
	 * @return Collection Collection to fill in drop-down
	 */
	public function getList() {
		$list = $this->orderBy('institute_name')->pluck('institute_name', 'inst_inquiry_id');

		return $this->parserResult($list);
	}

	/**
	 * Get institute inquiry details with details of last visit in that institute
	 *
	 * @param int|null $instInquiryId Institute inquiry id
	 * @param array    $where array of where conditions
	 * @param array    $columns columns to fetch in result
	 *
	 * @return array|Collection Returns details of institute inquiry
	 */
	public function  getDetailWithLatestVisitDetails( $instInquiryId = null, array $where = [], $columns = [])
	{
		$this->limit(1);
		$this->join(TABLE_BACKOFFICE_SALES_VISIT, TABLE_BACKOFFICE_SALES_VISIT.'.inst_inquiry_id','=', TABLE_BACKOFFICE_INST_INQUIRY.'.inst_inquiry_id');
		$this->orderBy(TABLE_BACKOFFICE_SALES_VISIT.'.inserted', 'desc');

		/* select columns from latest sales visit */
		$defaultColumns = [
			TABLE_BACKOFFICE_SALES_VISIT . '.contact_person',
			TABLE_BACKOFFICE_SALES_VISIT . '.contact_person_desig',
			TABLE_BACKOFFICE_SALES_VISIT . '.contact_person_email_id',
			TABLE_BACKOFFICE_SALES_VISIT . '.contact_person_phone'
		];

		/* Merge if new columns are supplied */
		if ( ! empty($columns) ) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		return $this->getDetail($instInquiryId, $where, $defaultColumns);
	}

	/**
	 * Get institute details
	 *
	 * @param int|null $instInquiryId Institute inquiry id
	 *
	 * @param array    $where         array of where conditions
	 * @param array    $columns       columns to fetch in result
	 * @param string   $method        Method to be used while fetching data
	 *
	 * @return mixed Returns details of institute inquiry
	 */
	public function getDetail( $instInquiryId = null, array $where = [], $columns = [], $method = 'get' ) {
		$availableMethod = ['get', 'first'];

		if ( ! in_array($method, $availableMethod)) {
			throw new InvalidArgumentException(trans('exception.invalid_parameters.message',
				['parameters' => $method]), trans('exception.invalid_parameters.code'));
		}

		$defaultColumns = [
			TABLE_BACKOFFICE_INST_INQUIRY . '.inst_inquiry_id',
			TABLE_BACKOFFICE_INST_INQUIRY . '.institute_name',
			TABLE_BACKOFFICE_INST_INQUIRY . '.address',
			TABLE_BACKOFFICE_INST_INQUIRY . '.city',
			TABLE_BACKOFFICE_INST_INQUIRY . '.state_id',
			TABLE_BACKOFFICE_INST_INQUIRY . '.student_strength',
			TABLE_BACKOFFICE_INST_INQUIRY . '.acq_status',
			TABLE_BACKOFFICE_INST_INQUIRY . '.converted_inst_id',
			TABLE_BACKOFFICE_INST_INQUIRY . '.inst_category_id',
			TABLE_BACKOFFICE_INST_INQUIRY . '.acq_date',
			TABLE_BACKOFFICE_INST_INQUIRY . '.acq_member_id'
		];

		if ( ! empty($defaultColumns) ) {
			$defaultColumns = array_merge($defaultColumns, $columns);
		}

		if ( ! GeneralHelpers::isNull($instInquiryId) ) {
			$results = $this->where(TABLE_BACKOFFICE_INST_INQUIRY . '.inst_inquiry_id', $instInquiryId)->first($defaultColumns);
		} else {
			$this->applyConditions($where);
			$results = $this->{$method}($defaultColumns);
		}

		return $this->parserResult($results);
	}

	/**
	 * Get available cities from institute inquiry
	 *
	 * @param string $term term to get matching city names
	 *
	 * @return Collection|null  Returns all matching cities
	 */
	public function getAvailableCities( $term = '' ) {
		$cities = $this->distinct()->select([ DB::raw('TRIM(city) `value`'), ]);
		if ( ! empty($term) ) {
			$cities->where('city', 'LIKE', '%' . $term . '%');
		}
		$result = $cities->orderBy('value')->get();

		return $this->parserResult($result);
	}

	/**
	 * Get institute details based on existing inquiry
	 *
	 * @param int $instInquiryId
	 *
	 * @return mixed Returns details of the institute which is not acquired yet
	 */
	public function getDetailOfNotAcquiredInstitute( $instInquiryId ) {
		$this->pushCriteria(InstituteNotAcquiredCrit::class);
		$result = $this->findWhere(['inst_inquiry_id' => $instInquiryId], [
			'inst_category_id',
			'student_strength',
			'address',
			'city',
			'state_id'
		])->first();

		return $result;
	}

	/**
	 * Create Inquiry
	 *
	 * @param array $inquiryData Array of data to create
	 *
	 * @return \stdClass|array|null Returns newly created inquiry entry
	 */
	public function createInquiry( $inquiryData ) {
		return $this->create($inquiryData);
	}

	/**
	 * Function to check whether institute is acquired or not
	 *
	 * @param int $instInquiryId Id of the institute inquiry
	 *
	 * @return bool status of institute acquisition
	 */
	public function isInstituteAcquired( $instInquiryId ) {
		$this->where('inst_inquiry_id', '=', $instInquiryId);
		if ( $this->value('acq_status') == 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Removes acquisition details of institute
	 *
	 * @param int $instInquiryId Id of the institute inquiry
	 * @param int $userId        Id of the user who is removing acquisition
	 *
	 * @return bool status of the operation
	 */
	public function removeInstituteAcquisition( $instInquiryId, $userId ) {
		$instInquiryData = [
			'acq_status'        => 0,
			'converted_inst_id' => NULL,
			'acq_date'          => NULL,
			'acq_member_id'     => NULL,
			'user_ip'           => Helper::getIPAddress(true),
			'updated'           => Helper::datetimeToTimestamp(),
			'updated_user'      => $userId
		];
		$instInquiryStatus = $this->updateInquiry($instInquiryData, $instInquiryId);

		return ($instInquiryStatus) ? true : false;
	}

	/**
	 * Update Inquiry
	 *
	 * @param array $inquiryData Array of data to be updated
	 * @param int   $inquiryId   Id of sales visit entry
	 *
	 * @return \stdClass|array|null Returns updated inquiry entry
	 */
	public function updateInquiry( $inquiryData, $inquiryId ) {
		return $this->updateById($inquiryData, $inquiryId);
	}

	/**
	 * Acquire the institute
	 *
	 * @param int $instInquiryId Id of the institute inquiry
	 * @param int $convertedInstituteId Id of the converted institute
	 * @param int $memberId Id of the member who is updating acquisition
	 * @param int $userId Id of the user who is updating acquisition
	 *
	 * @return bool status of the operation
	 */
	public function acquireInstitute( $instInquiryId, $convertedInstituteId, $memberId, $userId ) {
		$instInquiryData = [
			'acq_status'        => 1,
			'converted_inst_id' => $convertedInstituteId,
			'acq_date'          => Helper::datetimeToTimestamp(),
			'acq_member_id'     => $memberId,
			'user_ip'           => Helper::getIPAddress(true),
			'updated'           => Helper::datetimeToTimestamp(),
			'updated_user'      => $userId
		];
		$instInquiryStatus = $this->updateInquiry($instInquiryData, $instInquiryId);

		return  ( $instInquiryStatus ) ? true : false;
	}
}
