<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1.0', 'middleware' => ['api.auth']], function () {
	Route::group(['prefix' => 'services'], function () {
		Route::group(['prefix' => 'suggest'],function(){

			Route::get('courses/search/', ['as' => 'api.services.suggest.courses', 'uses' => "AutoSuggestController@suggestCourses"]);

			/**
			 * Get the courses of institute
			 *
			 * @apiParams string|int inst_id {required} Id of the institute
			 * @apiParams string q String you want to match in database
			 */
			Route::get('institute/courses/search/', ['as' => 'api.services.suggest.institute-courses', 'uses' => "AutoSuggestController@getInstituteCourses"]);

			/**
			 * Optional Parameters
			 *
			 *  @apiParams bool courses If true provide institutes having at least one course,
			 *                          otherwise provide all institutes
			 * @apiParams string q String you want to match in database
			 */
			Route::get('institute/search/', ['as' => 'api.services.suggest.institute', 'uses' => "AutoSuggestController@suggestInstitute"]);
		});
	});
});
