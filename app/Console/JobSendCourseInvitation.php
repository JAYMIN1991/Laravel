<?php

namespace App\Console;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Entities\CourseEntity;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Subscription\Repositories\Contracts\BackOfficeCourseInvitationRepo;
use Flinnt\Core\Console\BaseCommand;
use Helper;
use Log;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class JobSendCourseInvitation
 * @package App\Console
 */
class JobSendCourseInvitation extends BaseCommand {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'job:send-course-invitation
		{ backOfficeUser : Id of back-office user}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send course invitation';

	/**
	 * Subject of the email
	 *
	 * @var string
	 */
	protected $subject = 'ACTION REQUIRED - Send community course invitation ERROR';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$startTime = GeneralHelpers::getMicroTime();      // current timestamp in microtime format
		$timeNow = Helper::datetimeToTimestamp();         // current timestamp in unix timestamp format
		$print = false;
		$courseCache = [];
		if ( $this->hasOption('v') || $this->hasOption('verbose') ) {
			$print = true;
		}

		$this->line(str_repeat('=', 80));
		$this->info('Server Time: ' . Helper::timestempToDate($timeNow, 'r') . "\n");
		Log::info('Process started at: ' . Helper::getDate('l jS \of F Y h:i:s A'));

		/** @var BackOfficeCourseInvitationRepo $backOfficeCourseInvitationRepo */
		$backOfficeCourseInvitationRepo = App::make(BackOfficeCourseInvitationRepo::class);
		$invitations = $backOfficeCourseInvitationRepo->getCourseInvitationList();

		if ( ! $invitations->isEmpty() ) {
			// Update the status of invitation to `queue`
			$invitationIds = [];
			foreach ( $invitations as $invitation ) {
				$invitationIds[] = $invitation['invitation_id'];
			}

			$backOfficeCourseInvitationRepo->updateCourseInvitationStatus(BKOFF_COURSE_INVITE_QUEUED, $invitationIds);

			foreach ( $invitations as $invitation ) {
				// If course id is available in cache then take it from their, else query the database
				if ( ! isset($courseCache[$invitation['course_id']]) ) {
					$courseEntity = new CourseEntity($invitation['course_id']);
					$courseCache[$courseEntity->getCourseId()] = $inviterId = $courseEntity->getCourseOwner();
				} else {
					$inviterId = $courseCache[$invitation['course_id']];
				}

				$arguments = [
					'user_id'         => $inviterId,
					'course_id'       => $invitation['course_id'],
					'invite_role'     => CourseRepo::USER_COURSE_ROLE_LEARNER,
					'invite_user_id'  => $invitation['user_id'],
					'backoff_user_id' => $this->argument('backOfficeUser')
				];
				$eventStatus = event('send-invitation', array_values($arguments))[0];
				$courseInvitationData['invite_status'] = BKOFF_COURSE_INVITE_ERROR;
				$courseInvitationData['remarks'] = serialize(['params' => $arguments]);

				if ( array_key_exists('status', $eventStatus) && $eventStatus['status'] == 1 ) {
					$courseInvitationData['invite_status'] = BKOFF_COURSE_INVITE_PROCESSED;

				} elseif ( array_key_exists('errors', $eventStatus) && array_key_exists('code',
						$eventStatus['errors'])
				) {
					switch ( $eventStatus['errors']['code'] ) {
						case 504:
							// Invitation already sent
							$courseInvitationData['invite_status'] = BKOFF_COURSE_INVITE_DUPLICATE;
							break;
						case 505:
							// User already subscribed to course
							$courseInvitationData['invite_status'] = BKOFF_COURSE_INVITE_SUBSCRIBED;
							break;
						default:
							// Any other error
							$courseInvitationData['invite_status'] = BKOFF_COURSE_INVITE_ERROR;
							break;
					}

					$courseInvitationData['remarks'] = serialize(['params' => $arguments, 'result' => $eventStatus]);
				}

				$backOfficeCourseInvitationRepo->updateCourseInvitation($courseInvitationData,
					$invitation['invitation_id']);
				Log::info("Invitation status for {$invitation['invitation_id']}: {$courseInvitationData['invite_status']}");

				if ( $print ) {
					$this->info("Invitation status for {$invitation['invitation_id']}: {$courseInvitationData['invite_status']}");
				}
			}
		}

		if ( $print ) {
			$this->info('No pending invitation');
		}
		
		Log::info('No pending invitation');

		return $this->endCommand($startTime);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			['backOfficeUser', InputArgument::REQUIRED, 'Id of back-office user'],
		];
	}

	/**
	 * Call this method at the end of the command
	 *
	 * @param int  $startTime Start time of the command
	 * @param bool $terminate True will terminate the command
	 */
	public function endCommand( $startTime, $terminate = true ) {
		Log::info('Process ended at: ' . Helper::getDate('l jS \of F Y h:i:s A'));
		$timeDiff = GeneralHelpers::getMicrotimeDiff($startTime, GeneralHelpers::getMicroTime());
		$this->info("\nExecution took " . $timeDiff . ' seconds');
		$this->line(str_repeat('*', 80));

		if ( $terminate ) {
			exit(0);
		}
	}
}