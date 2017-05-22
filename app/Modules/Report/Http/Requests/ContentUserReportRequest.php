<?php

namespace App\Modules\Report\Http\Requests;

use App;
use App\Common\GeneralHelpers;
use App\Modules\Shared\Misc\ContentUserReportViewHelper;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request of content user report
 * Class ContentUserReportRequest
 * @package App\Modules\Report\Http\Requests
 */
class ContentUserReportRequest extends FormRequest {

	protected $importStatus = [];
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'source_institute_id'  => 'sometimes|required|institute',
			'target_institute_id'  => 'sometimes|institute',
			'course_import_status' => 'sometimes|in:' . implode(',', $this->importStatus),
			'form_submit'          => 'sometimes|in:search,export',
			'date_from'            => 'sometimes|date_format:' . trans('shared::config.date_format'),
			'date_to'              => 'sometimes|date_format:' . trans('shared::config.date_format')
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'source_institute_id'  => trans('report::content-user-report.index.source.institute'),
			'course_import_status' => trans('report::content-user-report.index.import_status'),
			'date_from'            => trans('report::content-user-report.index.date.from'),
			'date_to'              => trans('report::content-user-report.index.date.to')
		];
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'form_submit.in'        => trans('exception.something_wrong.message'),
			'date_from.date_format' => trans('report::content-user-report.errors.date_format'),
			'date_to.date_format'   => trans('report::content-user-report.errors.date_format')
		];
	}

	/**
	 * Get data to be validated from the request.
	 *
	 * @return array
	 */
	protected function validationData() {
		$input = [];

		/**
		 * If source and target institute id is available in input decode it and merge with input
		 */
		if ( $this->has('source_institute_id') ) {
			$input['source_institute_id'] = GeneralHelpers::decode($this->input('source_institute_id'));
		}

		if ( $this->has('target_institute_id') ) {
			$input['target_institute_id'] = GeneralHelpers::decode($this->input('target_institute_id'));
		}

		$this->merge($input);

		return parent::validationData();
	}

	/**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation() {
		$this->importStatus = [
			ContentUserReportViewHelper::COPY_CONTENT_JOB_NOT_STARTED,
			ContentUserReportViewHelper::COPY_CONTENT_JOB_RUNNING,
			ContentUserReportViewHelper::COPY_CONTENT_JOB_COMPLETED,
			ContentUserReportViewHelper::COPY_CONTENT_JOB_FAILED,
		];
	}


}
