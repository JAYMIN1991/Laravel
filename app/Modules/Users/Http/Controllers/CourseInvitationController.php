<?php

namespace App\Modules\Users\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Shared\Repositories\Contracts\UserMasterRepo;
use App\Modules\Subscription\Repositories\Contracts\BackOfficeCourseInvitationRepo;
use App\Modules\Users\Http\Requests\CourseInvitationRequest;
use DB;
use DBLog;
use Exception;
use Helper;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Session;

/**
 * Class CourseInvitationController
 * @package App\Modules\Users\Http\Controllers
 */
class CourseInvitationController extends Controller {

	/**
	 *
	 * @param \App\Modules\Users\Http\Requests\CourseInvitationRequest $request
	 *
	 * @return Factory|Response|View|RedirectResponse
	 */
	public function inviteUsers( CourseInvitationRequest $request ) {
		if ( $request->isMethod("POST") ) {
			$courseId = null;
			$loginIds = null;
			$courseId = $request->get('decrypted_course_id');

			if ( $request->has('emails') ) {
				$loginIds = $request->get('emails');
			}

			/* @var CourseRepo $courseRepo */
			$courseRepo = app(CourseRepo::class);
			$course = $courseRepo->getCoursesById($courseId);

			if ( empty($loginIds) ) {
				return redirect()
					->back()
					->withInput(array_merge($request->all(), ['course_name' => $course['course_name']]))
					->withErrors(trans('users::course-invitation.errors.login_id'));
			}

			DB::beginTransaction();
			try {
				foreach ( $loginIds as $email ) {
					$emailId = $email['email'];
					if ( filter_var($emailId, FILTER_VALIDATE_EMAIL) === false ) {
						continue;
					}

					$status = BKOFF_COURSE_INVITE_READY;
					$remarks = '';

					/* @var UserMasterRepo $userRepo */
					$userRepo = app(UserMasterRepo::class);
					$user = $userRepo->getUserByLoginId($emailId);

					if ( empty($user) ) {
						$status = BKOFF_COURSE_INVITE_NOUSER;
					}

					$attributes = [];
					$attributes['course_id'] = $courseId;
					$attributes['user_id'] = $user['user_id'];
					$attributes['login_id'] = $emailId;
					$attributes['invite_status'] = $status;
					$attributes['invite_bkoff_user'] = Session::get('user_id');
					$attributes['invite_dt'] = Helper::datetimeToTimestamp();
					$attributes['invite_bkoff_ip'] = $request->ip();
					$attributes['remarks'] = $remarks;

					/* @var BackOfficeCourseInvitationRepo $invitation */
					$invitation = App::make(BackOfficeCourseInvitationRepo::class);
					$invitation->inviteFromBackOffice($attributes);
				}
			}
			catch ( Exception $e ) {
				DB::rollBack();

				return redirect()
					->back()
					->withInput(array_merge($request->all(), ['course_name' => $course['course_name']]))
					->withErrors(trans('users::course-invitation.error.unexpected'));
			}
			DB::commit();

			GeneralHelpers::callCommandInBackground('job:send-course-invitation', [Session::get('user_id')]);
//			(, ['backOfficeUser' => Session::get('user_id')]);

			DBLog::save(LOG_MODULE_BKOFF_COURSE_INVITATIONS, NULL, 'course_invitation_send', $request->getRequestUri(), Session::get('user_id'));

			return view('users::course-invitation', ['message' => trans('users::course-invitation.success.invitation')]);
		}
// Keep it
//		DBLog::save(LOG_MODULE_BKOFF_COURSE_INVITATIONS, NULL,"course_invitation_view", $request->getRequestUri(), Session::get('user_id'));
		return view('users::course-invitation');
	}
}
