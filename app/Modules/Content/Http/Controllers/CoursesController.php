<?php

namespace App\Modules\Content\Http\Controllers;

use App;
use App\Common\URLHelpers;
use App\Common\GeneralHelpers;
use App\Modules\Content\Http\Requests\Courses as Requests;
use App\Modules\Content\Repositories\Contracts\CourseCategoriesRepo;
use App\Modules\Content\Repositories\Contracts\LMSContentsRepo;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Shared\Misc\ViewHelper;
use App\Modules\Shared\Misc\CoursesReviewViewHelper;
use Illuminate\Http\RedirectResponse;
use Redirect;
use Session;
use View;
use App\Http\Controllers\Controller;

/**
 * Class CoursesController
 * @package App\Modules\Content\Http\Controllers
 */
class CoursesController extends Controller {

	/**
	 * @var CourseRepo $courseRepo
	 */
	private $courseRepo;

	/**
	 * CoursesController constructor
	 */
	public function __construct() {
		$this->courseRepo = App::make(CourseRepo::class);
	}

	/**
	 * Listing Courses Review
	 *
	 * @param Requests\ReviewRequest $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function review( Requests\ReviewRequest $request ) {
		$defaultCourseReviewStatus = CoursesReviewViewHelper::SELECT_OPTION_REVIEW_PENDING;
		$institutes = [];
		$courseTypes = [
			ViewHelper::SELECT_COURSE_TYPE_TIME_BOUND => trans('content::course.review.time_bound'),
			ViewHelper::SELECT_COURSE_TYPE_SELF_PACED => trans('content::course.review.self_paced')
		];
		$courseReviewStatus = [
			CoursesReviewViewHelper::SELECT_OPTION_REVIEW_PENDING => trans('content::course.review.review_pending'),
			CoursesReviewViewHelper::SELECT_OPTION_APPROVED       => trans('content::course.review.approved'),
			CoursesReviewViewHelper::SELECT_OPTION_NOT_APPROVED   => trans('content::course.review.not_approved'),
			CoursesReviewViewHelper::SELECT_OPTION_DEACTIVATED    => trans('content::course.review.deactivated')
		];

		if ( ! empty($request->input('institute')) ) {
			$institutes = App::make(UserMasterRepo::class)
			                 ->getInstitutesListWhoHasPendingCourseReview($request->input('decrypted_institute'), null);

			$institutes = (! empty($institutes)) ? [$request->input('institute') => $institutes['user_school_name']] : [];
		}

		$courses = $this->courseRepo->searchCoursesForReview()
		                            ->appends($request->except('page', 'decrypted_institute'));
		$courses = GeneralHelpers::encryptColumns($courses, 'course_id');

		return View::make('content::courses.review', compact('courses', 'institutes', 'courseTypes', 'courseReviewStatus', 'defaultCourseReviewStatus'));
	}

	/**
	 * Show single course detail
	 *
	 * @param Requests\ShowRequest $request
	 *
	 * @return \Illuminate\Contracts\View\View|RedirectResponse
	 */
	public function show( Requests\ShowRequest $request ) {

		$courseId = $request->input('course');
		$courseStatus = $this->courseRepo->getCoursesById($courseId, ['course_status']);

		/* redirect back if course is deactivate */
		if ( ! empty($courseStatus) && $courseStatus['course_status'] == COURSE_STATUS_CLOSE ) {
			return Redirect::back()
			               ->withInput($request->except('course'))
			               ->withErrors(trans("content::course.show.error.deactivate"));
		}

		$courseDetails = $this->courseRepo->getFullCourseDetailWithUserAndLocationByCourseId($courseId);
		$userId = GeneralHelpers::encode(Session::get('user_id'));
		$courseCategories = App::make(CourseCategoriesRepo::class)->getCourseCategoriesByCourseId($courseId);

		/* check if course detail array is empty */
		if ( ! empty($courseDetails) ) {

			/* encode course_id */
			$courseDetails = GeneralHelpers::encryptColumns($courseDetails, ['course_id']);
			$previewURL = URLHelpers::getLMSLinkPreviewURL('%1$s', '%2$s');
			$downloadDocURL = URLHelpers::getLMSDocPreviewURL('%1$s');
			$contentDescriptionURL = URLHelpers::getAPIURL(API_APP_VERSION_1, API_LMS_UPDATE_SECTION_CONTENT_PREVIEW, 'app', '1', $courseId, '%1$d', '%2$d', '%3$d');
			$docPathURL = 'DIR_WS_RESOURCES_LMS_DOCS';

			$courseContentDetails = App::make(LMSContentsRepo::class)->getCourseContentByCourseId($courseId);

			if ( ! $courseContentDetails->isEmpty() ) {
				$courseContentDetails = GeneralHelpers::encryptColumns($courseContentDetails, [
					'section_id',
					'content_id',
					'attach_id'
				]);
			}

			return View::make('content::courses.show', compact('courseDetails', 'courseContentDetails', 'previewURL', 'downloadDocURL', 'contentDescriptionURL', 'docPathURL', 'userId', 'courseCategories'));
		} else {
			abort(404, trans('shared::message.error.not_found'));
		}
	}
}
