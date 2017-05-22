<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'publisher', 'middleware' => 'sentinel.auth'], function () {
	Route::get('cambridge/tkt/search/', ['as'   => 'publisher.cambridge.tkt.search',
	                                     'uses' => 'CambridgeTKTSearchController@index'
	]);
	Route::resource('cambridge/tkt', 'CambridgeTKTSearchController', [
		'except' => ['show', 'index'],
		'names'  => [
			'create'  => 'publisher.cambridge.tkt.create',
			'store'   => 'publisher.cambridge.tkt.store',
			'edit'    => 'publisher.cambridge.tkt.edit',
			'update'  => 'publisher.cambridge.tkt.update',
			'destroy' => 'publisher.cambridge.tkt.destroy'
		],
	]);

	Route::get('cambridge/linguaskill/search', ['as'   => 'publisher.cambridge.linguaskill.search',
	                                            'uses' => 'CambridgeLinguaSkillSearchController@index'
	]);

	Route::get('cambridge/registrations', ['as'   => 'publisher.cambridge.registrations',
	                                            'uses' => 'CambridgeRegistrationsController@index'
	]);

	Route::get('cambridge/submissions', ['as'   => 'publisher.cambridge.submissions',
	                                       'uses' => 'CambridgeSubmissionsController@index'
	]);

	Route::get('cambridge/submissions/view_submission/{sub_id}', ['as'   => 'publisher.cambridge.submissions.view_submission',
	                                                                  'uses' => 'CambridgeSubmissionsController@viewSubmission'
	]);

	Route::get('cambridge/submissions/download_submission/registration/{id}', ['as'   => 'publisher.cambridge.submissions.download_submission.registration',
	                                                                                           'uses' => 'CambridgeSubmissionsController@downloadRegistrationZip'
	]);

	Route::get('cambridge/submissions/download_submission/submission/{id}', ['as'   => 'publisher.cambridge.submissions.download_submission.submission',
	                                                                           'uses' => 'CambridgeSubmissionsController@downloadSubmissionZip'
	]);
});
