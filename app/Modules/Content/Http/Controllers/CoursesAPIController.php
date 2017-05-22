<?php

namespace App\Modules\Content\Http\Controllers;

use App;
use App\Common\ErrorCodes;
use App\Common\GeneralHelpers;
use App\Modules\Content\Http\Requests\Courses\API as Request;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;

/**
 * Class CoursesAPIController
 * @package App\Modules\Content\Http\Controllers
 */
class CoursesAPIController extends Controller {

	/**
	 *  Returns Status list for particular course
	 *
	 * @param Request\GetCourseStatusListRequest $request
	 *
	 * @return JsonResponse
	 */
	public function getCourseStatusList( Request\GetCourseStatusListRequest $request ) {
		$result = ['status' => 1, 'items' => ''];
		$courseId = $request->input('course');

		/* @var CourseRepo $courseDetail */
		$courseColumns = ['course_id', 'course_status', 'course_review_status'];
		$courseDetail = App::make(CourseRepo::class)->getCoursesById($courseId, $courseColumns);
		$statusList = [
			0 => ['text' => '--' . trans('content::course.review.status_placeholder') . '--', 'id' => 0],
			1 => ['text' => trans('content::course.review.accept_course'), 'id' => COURSE_REVIEW_ACCEPT],
			2 => ['text' => trans('content::course.review.reject_course'), 'id' => COURSE_REVIEW_REJECT],
			3 => ['text' => trans('content::course.review.deactivate_course'), 'id' => COURSE_REVIEW_DEACTIVATE],
		];
		$courseStatusList = [];

		if ( ! empty($courseDetail) ) {

			if ( $courseDetail['course_status'] == COURSE_STATUS_PUBLISH ) {
				$courseStatusList = array_only($statusList, [0, 3]);
			} elseif ( $courseDetail['course_status'] == COURSE_STATUS_CLOSE ) {
				$courseStatusList = array_only($statusList, [0, 1]);
			} else {

				switch ( $courseDetail['course_review_status'] ) {
					case COURSE_REVIEW_PENDING:
						$courseStatusList = $statusList;
						break;
					case COURSE_REVIEW_ACCEPT:
						$courseStatusList = array_only($statusList, [0, 2, 3]);
						break;
					case COURSE_REVIEW_REJECT:
						$courseStatusList = array_only($statusList, [0, 1, 3]);
						break;
					case COURSE_REVIEW_DEACTIVATE:
						$courseStatusList = array_only($statusList, [0, 1]);
						$courseStatusList[1] = trans('content::course.review.activate_course');
						break;
				}
			}

			$result['items'] = $courseStatusList;
			$result['status'] = 1;
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * @param Request\UpdateCourseStatusRequest $request
	 *
	 * @return JsonResponse
	 */
	public function updateStatus( Request\UpdateCourseStatusRequest $request ) {
		$result = ['status' => 0];
		$courseId = $request->input('course');
		$userId = $request->input('auth_user')['user_id'];
		$status = $request->input('status');
		$remarks = $request->input('remarks');

		$eventResult = event('review-course', [$courseId, $userId, $status, $remarks])[0];

		$result['status'] = $eventResult['status'];
		$result['message'] = ($eventResult['status'] == 1) ? trans('shared::message.success.process') : trans('content::course.review.error.action_failed');

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}

	/**
	 * Returns course review history
	 *
	 * @param Request\GetCourseReviewHistoryRequest $request
	 *
	 * @return JsonResponse
	 */
	public function getCourseReviewHistory( Request\GetCourseReviewHistoryRequest $request ) {
		$courseId = $request->input('course');
		$result = ['status' => 1, 'data' => ['history' => []]];
		$history = App::make(CourseRepo::class)->getCourseReviewHistory($courseId);

		if ( ! $history->isEmpty() ) {
			$result['data'] = ['history' => $history];
		}

		return $this->sendResponse($result, ErrorCodes::HTTP_OK);
	}


	/**
	 * Get course content details
	 *
	 * @param Request\GetCourseAttachmentDetailsRequest $request
	 *
	 * @return string
	 */
	public function getCourseAttachmentDetails( Request\GetCourseAttachmentDetailsRequest $request ) {
		$courseId = $request->input('course');
		$uri = 'lms/user/' . $request->input('user_id') . '/course/' . $courseId . '/section/' . $request->input('section_id') . '/content/' . $request->input('content_id') . '/attachment/' . $request->input('attachment_id');
		$result = [];
		$status = ErrorCodes::HTTP_OK;

		try {
			/* Calling Public API */
			$responseAPI = GeneralHelpers::callAPI($uri, 'GET', [], false);
			$response = GuzzleHttp\json_decode($responseAPI->getBody(), true);
			$result['status'] = $response['status'];

			if ( $responseAPI->getStatusCode() == 200 ) {

				/* If response code is 200, will get status and data fields */
				$result['data'] = $response['data'];
			} else {

				/* We will get message in case or bad request */
				$result['message'] = $response['message'];

				/* Based on environment we may get data in errors */
				if ( array_key_exists('errors', $response) ) {
					$result['errors'] = $response['errors'];
				}

				/* Set status to bad request, as we are getting 400+ status from api call */
				$status = ErrorCodes::HTTP_BAD_REQUEST;
			}
		} catch ( ClientException $e ) {

			$response = GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
			$result['status'] = $response['status'];
			$result['message'] = $response['message'];

			/* Based on environment we may get data in errors */
			if ( array_key_exists('errors', $response) ) {
				$result['errors'] = $response['errors'];
			}

			$status = ErrorCodes::HTTP_BAD_REQUEST;
		}

		return $this->sendResponse($result, $status);
	}
}
