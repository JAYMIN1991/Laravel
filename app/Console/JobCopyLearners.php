<?php

namespace App\Console;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Repositories\Contracts\BackOfficeJobResultsRepo;
use App\Modules\Shared\Repositories\Contracts\CourseRepo;
use App\Modules\Subscription\Repositories\Contracts\CourseSubscriptionRepo;
use Flinnt\Core\Console\BaseCommand;
use Helper;
use Log;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Translation\Exception\InvalidResourceException;

/**
 * Class JobCopyLearners
 * @package App\Console
 */
class JobCopyLearners extends BaseCommand {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'job:copy-learners 
        {jobId : Id of job } 
        {fromCourse : Id of from Course }
        {toCourse : Id of to course }
        { backOfficeUser : Id of back-office user}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Copy learners job';

	protected $subject = "ACTION REQUIRED - copy learners between courses ERROR";

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		$startTime = GeneralHelpers::getMicroTime();      // current timestamp in microtime format
		$timeNow = Helper::datetimeToTimestamp();         // current timestamp in unix timestamp format
		$print = false;
		list($jobId, $fromCourse, $toCourse, $backOfficeUser) = $this->getArgs();
		$jobResult = '';

		if ( $this->hasOption('v') || $this->hasOption('verbose') ) {
			$print = true;
		}

		$this->line(str_repeat('=', 80));
		$this->info('Server Time: ' . Helper::timestempToDate($timeNow, 'r') . "\n");
		Log::info("Process started at: " . Helper::timestempToDatetime(Helper::datetimeToTimestamp(),
				'l jS \of F Y h:i:s A'));
		Log::info("Copy Learners " . $fromCourse . " to " . $toCourse);

		$jobColumns = ['job_log'];
		$backOfficeJob = App::make(BackOfficeJobResultsRepo::class);
		$job = $backOfficeJob->getJob($jobId, $jobColumns);
		$jobLog = '';

		if ( ! empty($job['job_log']) ) {
			$jobLog = $job['job_log'] . "\n";
		}

		$backOfficeJob->updateJob(['job_status' => BACKOFFICE_JOB_STATUS_RUNNING], $jobId);

		$courseColumns = [
			'course_id',
			'course_name',
			'course_owner',
			'course_enabled',
			'course_status',
			'course_plan_expired',
			'course_public',
		];

		$courseRepo = App::make(CourseRepo::class);
		$fromCourse = $courseRepo->getFreeCoursesById($fromCourse, $courseColumns);

		if ( ! $fromCourse ) {
			throw new InvalidResourceException(trans('exception.resource_not_found.message',
				['resource' => 'From Course'], trans('exception.resource_not_found.code')));
		}

		$toCourse = $courseRepo->getFreeCoursesById($toCourse, $courseColumns);

		if ( ! $toCourse ) {
			throw new InvalidResourceException(trans('exception.resource_not_found.message',
				['resource' => 'To Course'], trans('exception.resource_not_found.code')));
		}

		$subscription = App::make(CourseSubscriptionRepo::class);
		$users = $subscription->getUserWithSubscription($fromCourse['course_id'], $toCourse['course_id']);

		if ( ! $users->isEmpty() ) {
			$message = "Total " . $users->count() . " learners found who are not registered in course " . $toCourse["course_name"] . "\n";
			$jobLog .= $message . "\n";
			if ( $print ) {
				$this->info($message);
			}
			Log::info($message);

			$subscribed = [];
			$failed = [];
			foreach ( $users as $user ) {
				$eventStatus = event('join-course',
					[$user['user_id'], $toCourse['course_id'], $backOfficeUser, $toCourse['course_public'], false])[0];
				if ( $eventStatus['status'] == 1 ) {
					$subscribed[] = $eventStatus['data'];
				} else {
					$failed[] = $eventStatus['data'];
				}
			}

			if ( empty($failed) && ! empty($subscribed) ) {

				$jobResult = "Total " . count($subscribed) . " learners copied successfully";
				$jobLog .= $jobResult . "\n";

				if ( $print ) {
					$this->info($jobResult);
				}

				Log::info($jobResult);
			} elseif ( ! empty($failed) && empty($subscribed) ) {

				$jobResult = "No learners have been copied. Total " . count($failed) . " failed";
				$jobLog .= $jobResult . "\n";

				if ( $print ) {
					$this->info($jobResult);
				}

				Log::info($jobResult);
			} elseif ( empty($failed) && empty($subscribed) ) {

				$jobResult = "No learners have been copied.";
				$jobLog .= $jobResult . "\n";

				if ( $print ) {
					$this->info($jobResult);
				}

				Log::info($jobResult);
			}

		} else {
			$jobResult = "WARNING!! It seems that all the learners of &quot;" . $fromCourse['course_name'] . "&quot; are already subscribed to &quot;" . $toCourse['course_name'] . "&quot; course";
			$jobLog .= $jobResult . "\n";
			if ( $print ) {
				$this->info($jobResult);
			}
			Log::info($jobResult);
		}

		$result = [];
		$result["job_status"] = BACKOFFICE_JOB_STATUS_FINISHED;
		$result["job_log"] = $jobLog;
		$result["job_result"] = $jobResult;
		$backOfficeJob->updateJob($result, $jobId);
		$this->endCommand($startTime, true);
		exit(0);
	}

	/**
	 * Call this method at the end of the command
	 *
	 * @param int  $startTime Start time of the command
	 * @param bool $terminate True will terminate the command
	 */
	public function endCommand( $startTime, $terminate = true ) {
		Log::info("Process ended at: " . Helper::timestempToDatetime(Helper::datetimeToTimestamp(),
				'l jS \of F Y h:i:s A'));
		$timeDiff = GeneralHelpers::getMicrotimeDiff($startTime, GeneralHelpers::getMicroTime());
		$this->info("\nExecution took " . $timeDiff . ' seconds');
		$this->line(str_repeat('*', 80));

		if ( $terminate ) {
			exit(0);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
			['jobId', InputArgument::REQUIRED, 'Id of job'],
			['fromCourse', InputArgument::REQUIRED, 'Id of from Course'],
			['toCourse', InputArgument::REQUIRED, 'Id of to course'],
			['backOfficeUser', InputArgument::REQUIRED, 'Id of back-office user'],
		];
	}

	/**
	 * Get the argument of the job
	 *
	 * @return array
	 */
	private function getArgs() {
		return [ $this->argument('jobId'), $this->argument('fromCourse'), $this->argument('toCourse'), $this->argument('backOfficeUser')];
	}
}