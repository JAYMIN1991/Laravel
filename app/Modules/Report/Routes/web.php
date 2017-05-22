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

Route::group(['prefix' => 'report', 'middleware' => 'sentinel.auth'], function () {

	Route::get('content/users/', ['as' => 'report.content.users', 'uses' => 'ContentUserReportController@index']);

	Route::get('institutions/', ['as' => 'report.institutions', 'uses' => 'InstituteListController@index']);

	Route::get('users/statistics/registration/',
		['as' => 'report.users.statistics.registration', 'uses' => 'UsersCountController@index']);

	Route::get('users/new/',
		['as' => 'report.users.new', 'uses' => 'UsersCountController@newUsers']);

});
