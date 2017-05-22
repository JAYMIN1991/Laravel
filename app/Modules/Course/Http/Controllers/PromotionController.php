<?php

namespace App\Modules\Course\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Course\Http\Requests\CoursePromotion as PromotionRequest;
use App\Http\Controllers\Controller;
use App\Modules\Course\Repositories\Contracts as Repository;
use App\Modules\Shared\Misc\CoursePromotionViewHelper;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use DBLog;
use Helper;
use Illuminate\Support\Collection;
use League\Flysystem\Exception;
use Redirect;
use Session;
use View;
use DB;

/**
 * Class PromotionController
 * @package App\Modules\Course\Http\Controllers
 */
class PromotionController extends Controller {

	/**
	 * @var Repository\CoursePromotionBannersRepo $coursePromotionBannersRepo
	 */
	private $coursePromotionBannersRepo;

	/**
	 * @var CourseRepo $courseRepo
	 */
	private $courseRepo;

	/**
	 * @var Repository\CoursePromotionLocations $coursePromotionLocationsRepo
	 */
	private $coursePromotionLocationsRepo;

	/**
	 * PromotionController constructor
	 *
	 * @param CourseRepo                              $courseRepo
	 * @param Repository\CoursePromotionBannersRepo   $coursePromotionBannersRepo
	 * @param Repository\CoursePromotionLocationsRepo $coursePromotionLocationsRepo
	 */
	public function __construct( CourseRepo $courseRepo,
	                             Repository\CoursePromotionBannersRepo $coursePromotionBannersRepo,
	                             Repository\CoursePromotionLocationsRepo $coursePromotionLocationsRepo ) {
		$this->courseRepo = $courseRepo;
		$this->coursePromotionBannersRepo = $coursePromotionBannersRepo;
		$this->coursePromotionLocationsRepo = $coursePromotionLocationsRepo;
	}

	/**
	 * Create promotion page with search criteria
	 *
	 * @param PromotionRequest\CreateRequest $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function createSearch( PromotionRequest\CreateRequest $request ) {

		return $this->getSearchPageResponse($request, 'create');
	}

	/**
	 * Page for searching course promotion that are already created
	 *
	 * @param PromotionRequest\IndexRequest $request Object search request
	 *
	 * @return \Illuminate\Contracts\View\View Returns view for search course promotion
	 */
	public function index( PromotionRequest\IndexRequest $request ) {

		return $this->getSearchPageResponse($request, 'index');
	}

	/**
	 * Search page to show courses before adding promotion
	 *
	 * @param PromotionRequest\ShowRequest $request
	 *
	 * @return string
	 */
	public function show( PromotionRequest\ShowRequest $request ) {
		$courseId = $request->input('decoded_course_id');
		$courseDetails = $this->courseRepo->getCourseAndCourseTypeByCourseId($courseId);

		/**@var Collection $promotionLocations */
		$promotionLocations = $this->coursePromotionLocationsRepo->getAllLocations();

		$coursePromotionLocations = $this->coursePromotionBannersRepo->getPromotionLocationsByCourseId($courseId);

		if ( ! $coursePromotionLocations->isEmpty() ) {
			$coursePromotionLocations = $coursePromotionLocations->pluck('location_id')->all();
		}

		DBLog::save(LOG_MODULE_COURSE_PROMOTION, null, 'view', $request->getUri(), Session::get('user_id'), $request->all());

		$courseId = $request->route('id'); //get encoded id back

		return View::make('course::promotion.show', compact('courseId', 'courseDetails', 'promotionLocations', 'coursePromotionLocations'));
	}

	/**
	 * @param PromotionRequest\StoreOrUpdateRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function storeOrUpdate( PromotionRequest\StoreOrUpdateRequest $request ) {
		$courseId = $request->input('decoded_course_id');

		try {
			DB::beginTransaction();

			/* Remove old locations */
			$this->coursePromotionBannersRepo->removePromotionLocationsByCourseId($courseId);
			DBLog::save(LOG_MODULE_COURSE_PROMOTION, $courseId, 'delete', $request->getUri(), Session::get('user_id'), $request->all());

			/* Prepare location data */
			$promotionLocationData['course_id'] = $courseId;
			$promotionLocationData['user_id'] = Session::get('user_id');
			$promotionLocationData['is_active'] = Repository\CoursePromotionBannersRepo::is_active;
			$promotionLocationData['inserted'] = Helper::datetimeToTimestamp();
			$promotionLocationData['inserted_user'] = Session::get('user_id');
			$promotionLocationData['user_ip'] = Helper::getIPAddress();
			$promotionLocationData['device_type'] = LOG_APP_BACK_OFFICE;

			/* Prepare insertedIds */
			$insertedId = [];

			/* Prepare course data */
			$coursePromotionData['course_promotion_rank'] = $request->input('display_order');

			/* Insert new locations */
			$locations = $request->input('location');

			if ( count($locations) > 0 ) {

				foreach ( $locations as $location ) {
					$promotionLocationData['location_id'] = $location;
					$bannerDetails = $this->coursePromotionBannersRepo->createPromotionLocation($promotionLocationData);

					if ( ! empty($bannerDetails) ) {
						$insertedId[] = $bannerDetails['course_promo_loc_id'];
					}

				}

				DBLog::save(LOG_MODULE_COURSE_PROMOTION, implode(',', $insertedId), 'insert', $request->getUri(), Session::get('user_id'), $request->all());
				$coursePromotionData['course_promotion'] = 1;
			} else {
				$coursePromotionData['course_promotion'] = 0;
			}

			/* Update course details */
			$this->courseRepo->updateCourseByCourseId($courseId, $coursePromotionData);
			DBLog::save(LOG_MODULE_COURSE, $courseId, 'course_promotion', $request->getUri(), Session::get('user_id'), $request->all());
			DB::commit();

			Return Redirect::back()->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			DB::rollBack();
			GeneralHelpers::logException($e);

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'));
		}
	}

	/**
	 * Remove promotion
	 *
	 * @param PromotionRequest\DestroyRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Redirect
	 */
	public function destroy( PromotionRequest\DestroyRequest $request ) {
		$courseId = $request->input('decoded_course_id');

		try {
			DB::beginTransaction();

			/* Remove from promotion banner */
			$this->coursePromotionBannersRepo->removePromotionLocationsByCourseId($courseId);

			$courseData['course_promotion'] = 0;
			$courseData['course_promotion_rank'] = 0;

			/* Update course promotion details */
			App::make(CourseRepo::class)->updateCourseByCourseId($courseId, $courseData);
			DBLog::save(LOG_MODULE_COURSE_PROMOTION, $courseId, 'delete', $request->getUri(), Session::get('user_id'), $request->all());
			DB::commit();

			return Redirect::back()->with('message', trans('shared::message.success.process'));
		} catch ( Exception $e ) {
			DB::rollBack();
			GeneralHelpers::logException($e);

			return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'));
		}
	}

	/**
	 * Get selected institute detail. this method will be used to show selected institute in post back.
	 *
	 * @param $instituteId
	 *
	 * @return array
	 */
	private function getSelectedInstituteDetail( $instituteId ) {

		$institute = App::make(UserMasterRepo::class)->getActiveInstituteHavingPublicCoursesByOwnerId($instituteId);

		if ( ! empty($institute) ) {
			$institute = GeneralHelpers::encryptColumns($institute, 'user_id');
			$institute = [$institute['user_id'] => $institute['user_school_name']];

			return $institute;
		} else {
			return [];
		}
	}

	/**
	 * Get institute courses to fill the course drop-down
	 *
	 * @param $instituteId
	 *
	 * @return array
	 */
	private function getInstituteCourses( $instituteId ) {
		$courses = App::make(CourseRepo::class)->getInstituteCoursesForPromotion($instituteId, true);

		if ( ! $courses->isEmpty() ) {
			$courses = GeneralHelpers::encryptColumns($courses->pluck('course_name', 'id'));
		} else {
			$courses = [];
		}

		return $courses;
	}

	/**
	 * Get response for search page. This function will generate response for index and create search pages.
	 *
	 * @param        $request provide current request object
	 * @param string $page    provide page name for which response to be generated. options: index, create. default is index
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	private function getSearchPageResponse( $request, $page = 'index' ) {
		$selectedCourseId = null;
		$priceTypes = [
			CoursePromotionViewHelper::COURSE_PAID => trans('course::promotion.common.paid'),
			CoursePromotionViewHelper::COURSE_FREE => trans('course::promotion.common.free')
		];
		$publicTypes = [
			ViewHelper::SELECT_COURSE_TYPE_TIME_BOUND => trans('course::promotion.common.time_bound'),
			ViewHelper::SELECT_COURSE_TYPE_SELF_PACED => trans('course::promotion.common.self_paced')
		];
		$promotedOnly = true;
		$logAction = 'index-search';

		/* If rendering view for create page then also allow not-promoted institute's courses */
		if ( $page == 'create' ) {
			$promotedOnly = false;
			$logAction = 'create-search';
		}

		/**@var Collection $promotionLocations */
		$promotionLocations = $this->coursePromotionLocationsRepo->getAllLocations();

		$httpServerCatalog = HTTP_SERVER_CATALOG;

		if ( $request->exists('button_search') ) {

			/* If get institute is request, fetch it and courses */
			if ( $request->has('institute_id') ) {
				$institute = $this->getSelectedInstituteDetail($request->input('decoded_institute_id'));

				if ( ! empty($institute) ) {
					$selectedCourseId = $request->input('course_id');

					/**@var Collection $courses */
					$courses = $this->getInstituteCourses($request->input('decoded_institute_id'));
				}
			}

			/* Fetch searched courses */
			$coursesDetails = $this->courseRepo->searchCoursesForPromotion($promotedOnly)
			                                   ->appends($request->except('page', 'decoded_institute_id', 'decoded_course_id'));
			if ( ! empty($coursesDetails) ) {
				$coursesDetails = GeneralHelpers::encryptColumns($coursesDetails, 'course_id');
			}
		}
		DBLog::save(LOG_MODULE_COURSE_PROMOTION, null, $logAction, $request->getUri(), Session::get('user_id'), $request->all());

		return View::make('course::promotion.' . $page, compact('institute', 'coursesDetails', 'priceTypes', 'publicTypes', 'courses', 'selectedCourseId', 'httpServerCatalog', 'promotionLocations'));
	}
}
