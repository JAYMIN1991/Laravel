<?php
namespace App\Modules\Publisher\Http\Controllers;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Publisher\Repositories\Contracts\CambridgeRegistrationRepo;
use App\Modules\Publisher\Repositories\Contracts\CambridgeSubmissionsRepo;
use App\Modules\Shared\Misc\CambridgeSubmissionActivityViewHelper;
use App\Modules\Shared\Misc\CambridgeSubmissionCategoryViewHelper;
use Chumper\Zipper\Zipper;
use DBLog;
use Exception;
use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use Session;
use View;

/**
 * Class CambridgeSubmissionsController
 * @package App\Modules\Publisher\Http\Controllers
 */
class CambridgeSubmissionsController extends Controller {

	protected $zipper;

	/**
	 * CambridgeSubmissionsController constructor.
	 */
	public function __construct() {
		// Initialize zipper class
		$this->zipper = new Zipper;
	}

	/**
	 * For Cambridge Submission page listing page
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index( Request $request ) {
		// Cambridge submission repo
		$cambridgeSubmissionsRepo = App::make(CambridgeSubmissionsRepo::class);

		// Cambridge Submission repo
		$cambridgeRegistrationRepo = App::make(CambridgeRegistrationRepo::class);
		// Get registration options
		$registrationOptions = $cambridgeRegistrationRepo->getCambridgeRegistrationNameList()
		                                                 ->pluck('CONCAT(reg_name, " - ", reg_mobile)', 'reg_id');

		// Activity List options
		$activityOptions = array(
			CambridgeSubmissionActivityViewHelper::CAMBRIDGE_SUBMISSION_ACTIVITY_EXAM      => trans('publisher::submissions.index.activity_exam'),
			CambridgeSubmissionActivityViewHelper::CAMBRIDGE_SUBMISSION_ACTIVITY_SUBSKILLS => trans('publisher::submissions.index.activity_subskills'),
			CambridgeSubmissionActivityViewHelper::CAMBRIDGE_SUBMISSION_ACTIVITY_LANGUAGE  => trans('publisher::submissions.index.activity_language')
		);

		// Category list options
		$categoryOptions = array(
			CambridgeSubmissionCategoryViewHelper::CAMBRIDGE_SUBMISSION_CATEGORY_LEARNER  => trans('publisher::submissions.index.young_learner'),
			CambridgeSubmissionCategoryViewHelper::CAMBRIDGE_SUBMISSION_CATEGORY_SCHOOL   => trans('publisher::submissions.index.middle_school_learner'),
			CambridgeSubmissionCategoryViewHelper::CAMBRIDGE_SUBMISSION_CATEGORY_TERTIARY => trans('publisher::submissions.index.tertiary_education_learner')
		);

		// Check if form submitted
		if ( $request->has('btnsearch') && $request->has('btnsearch') == 'submit' ) {
			// cambridge submission result
			$cambridgeSubmissionsResult = $cambridgeSubmissionsRepo->getCambridgeRegistrationSubmissionsResult(true);
			if ( ! $cambridgeSubmissionsResult->isEmpty() ) {
				foreach ( $cambridgeSubmissionsResult as $key => $value ) {

					if ( $value['submission_id'] ) {
						$value['enc_submission_id'] = GeneralHelpers::encode($value['submission_id']);
						$value['view_submission_url'] = route('publisher.cambridge.submissions.view_submission', ['sub_id' => GeneralHelpers::encode($value['submission_id'])]);
					}
					$cambridgeSubmissionsResult[$key] = $value;
				}
			}
			// Insert DB log for export tracking
			DBLog::save(LOG_MODULE_CAMBRIDGE_SUBMISSION, $request->input('btnexport'), 'Search', $request->getRequestUri(), Session::get('user_id'), $cambridgeSubmissionsResult);
		}

		if ( $request->has('btnexport') && $request->has('btnexport') == 'export' ) {
			try {
				$cambridgeSubmissionsReport = $cambridgeSubmissionsRepo->getCambridgeRegistrationSubmissionsResult(false);

				// Export column name
				$exportColumnNames = [
					'submission_id'         => 'SrNo',
					'reg_name'              => 'Name',
					'reg_email'             => 'EmailID',
					'reg_mobile'            => 'MobileNumber',
					'reg_institute'         => 'NameofInstitution',
					'reg_designation'       => 'Designation',
					'reg_experience'        => 'Experience',
					'reg_date'              => 'RegistrationDate',
					'sub_exam_category'     => 'Category',
					'sub_activity_type'     => 'ActivityType',
					'sub_description'       => 'Description',
					'sub_time_required'     => 'TimeRequired',
					'sub_material_required' => 'MaterialRequired',
					'sub_aims'              => 'Aims',
					'sub_procedure'         => 'Procedure',
					'sub_place'             => 'Place',
					'sub_date'              => 'SubmissionDate'
				];

				// Insert DB log for export tracking
				DBLog::save(LOG_MODULE_CAMBRIDGE_SUBMISSION, $request->input('btnexport'), 'Export', $request->getRequestUri(), Session::get('user_id'), $cambridgeSubmissionsReport);

				// Check is data exist to export
				if ( ! $cambridgeSubmissionsReport->isEmpty() ) {
					// export functionality
					GeneralHelpers::exportToExcel($exportColumnNames, $cambridgeSubmissionsReport->all(), FILENAME_CONSTANT_CAMBRIDGE_SUBMISSION_REPORT);
				} else {
					return Redirect::back()->with('message', trans('shared::message.error.nothing_to_export'));
				}

			} catch ( Exception $e ) {
				GeneralHelpers::logException($e);

				return Redirect::back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
			}
		}
		// Collection data to view file
		$cambridgeSubmissionsData = compact('registrationOptions', 'activityOptions', 'categoryOptions', 'cambridgeSubmissionsResult');

		return View::make('publisher::submissions.index', $cambridgeSubmissionsData);
	}


	/**
	 * Get submission page data view using submission id
	 * @param $submissionId for view submission details page.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function viewSubmission( $submissionId ) {
		// Get submission ID
		$submissionId = GeneralHelpers::clearParam(GeneralHelpers::decode($submissionId), PARAM_INT);

		// Cambridge Submission repository
		$cambridgeSubmissionsRepo = App::make(CambridgeSubmissionsRepo::class);

		// Cambridge Submission repo
		$cambridgeRegistrationRepo = App::make(CambridgeRegistrationRepo::class);
		if ( $submissionId ) {
			// Check is Registration Id exist
			$isRegistrationIdExist = $cambridgeSubmissionsRepo->checkSubmissionDataBySubmissionId($submissionId)[0];
			if ( $isRegistrationIdExist ) {

				// Get Cambridge Registration Data
				$cambridgeRegistrationData = $cambridgeRegistrationRepo->getCambridgeRegistrationSearch(false, $isRegistrationIdExist)[0];

				// Get Cambridge Submission data
				$cambridgeSubmissionData = $cambridgeSubmissionsRepo->getSubmissionViewData($isRegistrationIdExist);

				if ( ! empty($cambridgeRegistrationData) ) {
					if ( $isRegistrationIdExist ) {
						$cambridgeRegistrationData['enc_reg_id'] = GeneralHelpers::encode($isRegistrationIdExist);
					}
					if ( $cambridgeRegistrationData['reg_date'] ) {
						$cambridgeRegistrationData['reg_date'] = (string) Helper::timestempToDate($cambridgeRegistrationData['reg_date'], 'd F Y h:i a');
					}
				}

				// If cambridge Submission Data found from DB then insert some variable with different format
				if ( ! empty($cambridgeSubmissionData) ) {
					$submissionDataLength = count($cambridgeSubmissionData);
					foreach ( $cambridgeSubmissionData as $key => $value ) {
						// Check attachments available
						if ( $value['attachments'] ) {
							$attachments = explode('*ROW*', $value['attachments']);
							$value['total_attachments'] = count($attachments);
							// Attachments
							$i = 1;
							// Make downloadable file for each attachments
							foreach ( $attachments as $attachmentIteams ) {
								$file = explode('*COL*', $attachmentIteams);
								$fileDownload = asset('/Cambridge_English/static/submissions/submission-' . $value['submission_id'] . '/' . $file[1]);
								$value['attachment'][$i]['download_single_submission_url'] = $fileDownload;
								$value['attachment'][$i]['submission_file_name'] = $file[0];
								$i++;
							}
						}

						// encrypt Submission id
						if ( $value['submission_id'] ) {
							$value['enc_submission_id'] = GeneralHelpers::encode($value['submission_id']);
						}
						// Filter date variables
						if ( $value['sub_date'] ) {
							$value['sub_date'] = (string) Helper::timestempToDate($value['sub_date'], 'd F Y h:i a');
							$value['sub_date_only'] = (string) Helper::timestempToDate($value['sub_date'], 'd M Y');
							$value['sub_time_only'] = (string) Helper::timestempToDate($value['sub_date'], 'h:i a');
						}

						switch ( $value['sub_exam_category'] ) {
							case 'learner':
								$value['sub_exam_category'] = 'Young Learners';
								break;
							case 'school':
								$value['sub_exam_category'] = 'Middle School Learners';
								break;
							case 'tertiary':
								$value['sub_exam_category'] = 'Tertiary/Higher Education Learners';
								break;
						}

						switch ( $value['sub_activity_type'] ) {
							case 'exam':
								$value['sub_activity_type'] = 'Exam';
								break;
							case 'subskill':
								$value['sub_activity_type'] = 'Sub-skills';
								break;
							case 'language':
								$value['sub_activity_type'] = 'Language';
								break;
						}
						$cambridgeSubmissionData[$key] = $value;
					}
				}
			}

		}
		$submissionData = compact('cambridgeRegistrationData', 'cambridgeSubmissionData', 'submissionDataLength');

		return View::make('publisher::submissions.view', $submissionData);
	}

	/**
	 * Get registration data to download zip
	 * @param $registrationId for registration id to download zip
	 *
	 * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	public function downloadRegistrationZip( $registrationId ) {
		if ( ! empty($registrationId) ) {
			// Cambridge Submission repository
			$cambridgeSubmissionsRepo = App::make(CambridgeSubmissionsRepo::class);

			// Get decoded Registration Id
			$registrationId = GeneralHelpers::decode($registrationId);

			if ( $registrationId ) {
				// Get downloadable submission data
				$cambridgeRegistrationDownloadRecords = $cambridgeSubmissionsRepo->downloadRegistrationZip($registrationId);
			}

			if ( ! $cambridgeRegistrationDownloadRecords->isEmpty() ) {

				$file_name = 'download/sub-all-' . $registrationId . '-' . date('YmdHis-') . uniqid('r', false) . '.zip';

				foreach ( $cambridgeRegistrationDownloadRecords as $key => $value ) {
					// Get Files from particular submission data
					// @ToDo remember in production mode we have to change directory path as per source
					$filesList = glob('../../Cambridge_English/static/submissions/submission-' . $value['submission_id'] . '/*');
					$this->zipper->make($file_name)->add($filesList);
				}
				$this->zipper->close();

				// Force to download zip and delete after send to download
				return response()->download(public_path($file_name))->deleteFileAfterSend(true);
			} else {
				return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
			}
		} else {
			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}

	/**
	 * Get submission Data to download zip
	 * @param $submissionId for submission id download zip
	 *
	 * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	public function downloadSubmissionZip( $submissionId ) {
		if ( ! empty($submissionId) ) {
			// Cambridge Submission repository
			$cambridgeSubmissionsRepo = App::make(CambridgeSubmissionsRepo::class);
			// Decrypted submission id
			$submissionId = GeneralHelpers::decode($submissionId);
			// Get downloadable record set of submission
			$cambridgeSubmissionDownloadRecords = $cambridgeSubmissionsRepo->downloadSubmissionZip($submissionId);

			if ( ! $cambridgeSubmissionDownloadRecords->isEmpty() ) {
				// Get decoded Registration Id
				$file_name = 'download/sub-' . $submissionId . '-' . date('YmdHis-') . uniqid('s', false) . '.zip';
				foreach ( $cambridgeSubmissionDownloadRecords as $key => $value ) {
					// Get Files from perticular submission data
					// @ToDo remember in production mode we have to change directory path as per source
					$filesList = glob('../../Cambridge_English/static/submissions/submission-' . $value['submission_id'] . '/*');
					$this->zipper->make($file_name)->add($filesList);
				}
				$this->zipper->close();

				// Force to download zip and delete after send to download
				return response()->download(public_path($file_name))->deleteFileAfterSend(true);
			} else {
				return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
			}
		} else {
			return redirect()->back()->withErrors(trans('shared::message.error.something_wrong'))->withInput();
		}
	}
}
