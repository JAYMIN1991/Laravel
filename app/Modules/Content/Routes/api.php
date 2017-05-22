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

Route::group(['prefix' => 'v1.0/content', 'middleware' => ['api.auth']], function () {

	Route::group(['prefix' => 'courses/{id}'], function () {

		/* Route to get course status list */
		Route::get('status/search', [
			'uses' => 'CoursesAPIController@getCourseStatusList',
			'as'   => 'api.content.course.status.search'
		]);

		/* Route to update course status */
		Route::match(['put'], 'status', [
			'uses' => 'CoursesAPIController@updateStatus',
			'as'   => 'api.content.course.status.update'
		]);

		/* Route to get course review history */
		Route::get('review/search', [
			'uses' => 'CoursesAPIController@getCourseReviewHistory',
			'as'   => 'api.content.course.review.search'
		]);

		/* Route to get attachment details */
		Route::get('/section/{section_id}/content/{content_id}/attachment/{attachment_id}/', [
			'uses' => 'CoursesAPIController@getCourseAttachmentDetails',
			'as'   => 'api.content.course.section.content.attachment.show'
		]);

	});
});