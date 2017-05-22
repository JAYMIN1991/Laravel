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

Route::group(['prefix' => 'content', 'middleware' => 'sentinel.auth'], function () {

	/* Route to show contents review page */
	Route::get('courses/review/', ['as' => 'content.courses.review', 'uses' => 'CoursesController@review']);

	/* Route to show content details */
	Route::get('courses/{id}/', ['as' => 'content.courses.show', 'uses' => 'CoursesController@show']);
});
