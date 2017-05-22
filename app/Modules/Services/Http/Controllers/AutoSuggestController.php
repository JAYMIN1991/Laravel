<?php

namespace App\Modules\Services\Http\Controllers;

use App;
use App\Common\ErrorCodes;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Course\Repositories\Contracts\CourseOfflinePaymentRepo;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Users\Repositories\Contracts\UserRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use Illuminate\Http\Request;

/**
 * Class AutoSuggestController
 *
 * @package     App\Modules\Services\Http\Controllers
 * @deprecated  all autosuggestion call are now written ub api.php file of each module
 */
class AutoSuggestController extends Controller {

	/**
	 * Return the list of courses matching provided text in url
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function suggestCourses( Request $request ) {
		$result = ['status' => 0, 'items' => ''];

		if ( $request->has('term') ) {
			$name = $request->get('term');

			/* @var CourseRepo $courseRepo */
			$courseRepo = App::make(CourseRepo::class);
			$courses = $courseRepo->getCourses($name, true);

			if ( ! $courses->isEmpty() ) {
				// Encrypt the course id
				$courses = GeneralHelpers::encryptColumns($courses, 'id');
				$result['status'] = 1;
				$result['items'] = $courses;
			}

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

	/**
	 * Return the list of courses matching provided text in url and institute id
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getInstituteCourses( Request $request ) {
		$result = ['status' => 0, 'items' => ''];

		if ( $request->has('inst_id') ) {
			$instituteId = GeneralHelpers::decode($request->get('inst_id'));

			$for = true;
			$deleted = false;
			if ( $request->has('for') ) {
				$for = $request->get('for');
			}

			if ( $request->has('deleted') && $request->get('deleted') == true ) {
				$deleted = true;
			}

			/* @var CourseRepo $courseRepo */
			$courseRepo = App::make(CourseRepo::class);

			/* @var CourseOfflinePaymentRepo $offlinePaymentRepo */
			$offlinePaymentRepo = App::make(CourseOfflinePaymentRepo::class);

			/**
			 * If learners_count is true in request, it will return course name with learners count.
			 * Otherwise return the course name with institute name
			 */
			if ( $request->has('learners_count') && $request->get('learners_count') == true ) {
				$courses = $courseRepo->getInstituteCoursesWithLearnerCount($instituteId, true);
			} elseif ( $for && $for === 'copiedFrom' ) {
				$courses = $courseRepo->getInstituteCoursesFromWhichContentCopied($instituteId, true, $deleted);
			} elseif ( $for && $for === 'copiedTo' ) {
				$courses = $courseRepo->getInstituteCoursesToWhichContentCopied($instituteId, true, $deleted);
			} elseif ( $for && $for === 'promotion' ) {
				$courses = $courseRepo->getInstituteCoursesForPromotion($instituteId, true);
			} elseif ( $for && $for === 'offlinePayment' ) {
				$courses = $offlinePaymentRepo->getOfflinePaymentCoursesByInstituteId($instituteId);
			} else {
				$courses = $courseRepo->getInstituteCourses($instituteId, true);
			}

			if ( ! $courses->isEmpty() ) {
				// Encrypt the course id
				$courses = GeneralHelpers::encryptColumns($courses, 'id');
				$result['status'] = 1;
				$result['items'] = $courses;
			}

			return $this->sendResponse($result, ErrorCodes::HTTP_OK, [], JSON_UNESCAPED_UNICODE);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

	/**
	 * Return the list of institutes matching provided text in url
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function suggestInstitute( Request $request ) {
		$result = ['status' => 0, 'items' => ''];

		if ( $request->has('term') ) {
			$term = GeneralHelpers::clearParam($request->get('term'), PARAM_RAW_TRIMMED);

			/* @var UserMasterRepo $userMasterRepo */
			$userMasterRepo = App::make(UserMasterRepo::class);

			/* @var UserRepo $userRepo */
			$userRepo = App::make(UserRepo::class);

			$for = ($request->has('for')) ? $request->get('for') : null;

			switch ( $for ) {
				/**
				 * It will return institute having at least one course
				 */
				case 'courses' :
					$institutes = $userMasterRepo->getInstitutesHavingCourses($term, true);
					break;

				/**
				 *  It will return institute list for after sales visit
				 */
				case 'after_sales_visit' :
					$institutes = $userRepo->getInstituteListForAfterSalesVisit($term, true, true);
					break;

				/**
				 *  It will return institute list for after sales visit and only institute having after sales visit entry
				 */
				case 'after_sales_visit_list' :
					$institutes = $userRepo->getInstituteListForAfterSalesVisit($term, true, true, true);
					break;
				/**
				 * It will return institute list for acquisition page
				 */
				case 'acquisition' :
					$institutes = $userRepo->getNotAcquiredInstituteList($term, true, true);
					break;
				/**
				 *
				 */
				case 'copiedTo':
					$institutes = $userMasterRepo->getInstitutesToWhichCourseCopied($term, true);
					break;
				/**
				 *
				 */
				case 'copiedFrom':
					$institutes = $userMasterRepo->getInstitutesFromWhichCourseCopied($term, true);
					break;
				/**
				 * It will returns all institute having public courses, and pending for review
				 */
				case 'instituteWithPendingCourseReview':
					$institutes = $userMasterRepo->getInstitutesListWhoHasPendingCourseReview(null, $term, true);
					break;

				/**
				 * It will returns all active institutes having public course
				 */
				case 'activeInstituteUsersHavingPublicCourse':
					$institutes = $userMasterRepo->getActiveInstitutesHavingPublicCourses($term, true);
					break;

				/**
				 * Get all institute
				 */
				case 'all':
					$institutes = $userMasterRepo->getAllInstitutions($term, true);
					break;
				/**
				 * Returns all active institutes
				 */
				default:
					$institutes = $userMasterRepo->getActiveInstituteList($term, true);
			}

			if ( ! $institutes->isEmpty() ) {
				// Encrypt the institute id
				$institutes = GeneralHelpers::encryptColumns($institutes, 'id');
				$result['status'] = 1;
				$result['items'] = $institutes;
			}

			return $this->sendResponse($result, ErrorCodes::HTTP_OK);
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_BAD_REQUEST);
	}

}
