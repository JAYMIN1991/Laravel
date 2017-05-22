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

Route::group(['prefix' => 'user/public', 'middleware' => 'sentinel.auth'], function () {

	// View of the verification pending page
	Route::get('/verification/pending/', [
		'as'   => 'users.verification-pending.unverifiedAccountsList',
		'uses' => 'AccountVerificationController@unverifiedAccountsList'
	]);

	// Invite the user to specific course
	Route::match(['get', 'post'], '/invite/', [
		'as'   => 'users.course-invitation.inviteUsers',
		'uses' => 'CourseInvitationController@inviteUsers'
	]);

	// Search the public user
	Route::get('/search/', [
		'as'   => 'users.user-search.index',
		'uses' => 'UserSearchController@index'
	]);

	// Get the list of institute users
	Route::get('/institute/users/', [
		'as'   => 'users.institute-users.index',
		'uses' => 'InstituteUsersListController@index'
	]);

	// Copy users of course/s to another course
	Route::match(['get', 'post'], '/copy/', [
		'as'   => 'users.copy-learners.index',
		'uses' => 'CopyLearnersController@index'
	]);
});
