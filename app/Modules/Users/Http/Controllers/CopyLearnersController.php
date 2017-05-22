<?php

namespace App\Modules\Users\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Repositories\Contracts\BackOfficeJobResultsRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Users\Http\Requests\CopyLearnersRequest;
use App\Http\Controllers\Controller;
use DBLog;
use Illuminate\Support\Collection;
use Session;

/**
 * Class CopyLearnersController
 * @package App\Modules\Users\Http\Controllers
 */
class CopyLearnersController extends Controller {

	/**
	 * Get the view of copy learner page and copy learner from one or more courses to a course
	 *
	 * @param \App\Modules\Users\Http\Requests\CopyLearnersRequest $request
	 *
	 *
	 * @return \Illuminate\View\View
	 */
	public function index( CopyLearnersRequest $request ) {
		if ( $request->isMethod('POST') ) {

			DBLog::save(LOG_MODULE_COPY_LEARNERS, null, 'copy_learners', $request->getRequestUri(), Session::get('user_id'),
				$request->all());

			$errors = [];
			$success = [];
			/** @var Collection $toCourse */
			/** @var Collection $fromCourses */
			list($fromInstitute, $fromCourses, $toInstitute, $toCourse) = GeneralHelpers::getRequestData($request, [
				'from_institute',
				'from_courses',
				'to_institute',
				'to_course'
			]);
			$toCourse = $toCourse->first();

			$userMasterRepo = App::make(UserMasterRepo::class);
			$fromInstituteName = $userMasterRepo->getInstituteByOwnerId($fromInstitute)['user_school_name'];
			$toInstituteName = $userMasterRepo->getInstituteByOwnerId($toInstitute)['user_school_name'];
			$backOfficeJobRepo = App::make(BackOfficeJobResultsRepo::class);
			$userId = Session::get('user_id');

			$failedJobs = [];
			foreach ( $fromCourses as $fromCourse ) {

				$jobDescription = trans('users::copy-learners.index.initialize_copy',
					['from_course' => $fromCourse['course_name'], 'to_course' => $toCourse['course_name']]);
				$jobParameters = [
					'from_institute' => $fromInstitute,
					'from_course'    => $fromCourse['course_id'],
					'to_institute'   => $toInstitute,
					'to_course'      => $toCourse['course_id'],
					'user_id'        => $userId
				];

				$jobId = $backOfficeJobRepo->initializeJob(BACKOFFICE_JOB_COPY_LEARNERS, $jobDescription, $userId,
					$jobParameters);

				if ( $jobId ) {
					GeneralHelpers::callCommandInBackground('job:copy-learners',
						[$jobId, $fromCourse['course_id'], $toCourse['course_id'], $userId]);
					$success[] = (trans('users::copy-learners.success.job_init', ['job_id' => $jobId]));
				} else {
					$failedJobs[] = $fromCourse['course_name'];
				}
			}

			$failedJobsCount = count($failedJobs);
			if ( $failedJobsCount ) {
				if ( $fromCourses->count() == $failedJobsCount ) {
					$errors[] = trans('users::copy-learners.error.executing_job');
				} else {
					$errors[] = trans('users::copy-learners.error.executing_job_courses',
						['courses' => implode(', ', $failedJobs)]);
				}
			}

			return view('users::copy-learners', compact('fromInstituteName', 'toInstituteName'))->with('message', $success)->withErrors($errors);
		}

		return view('users::copy-learners', compact('fromInstituteName', 'toInstituteName'));
	}
}
