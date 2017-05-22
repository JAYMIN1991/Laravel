<?php

use Illuminate\Http\Request;

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

Route::get('/user', function ( Request $request ) {
	// return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'v1.0', 'middleware' => ['decrypt.id', 'api.auth']], function () {

	Route::group(['prefix' => 'users/public'], function () {

		// Password reset API for user search page.
		Route::post('{id}/password/reset', [
			"as"   => "users.password.reset",
			"uses" => "API\UserSearchAPIController@passwordReset"
		]);

		// Add remarks API for user search page.
		Route::post('{id}/remarks/add', [
			"as"   => "users.remarks.add",
			"uses" => "API\UserSearchAPIController@addRemarks"
		]);

		// Reset the email of user
		Route::post('{id}/email/reset', [
			"as"   => "users.email.reset",
			"uses" => "API\InstituteUsersListAPIController@changeEmail"
		]);

		// Reset the mobile of user
		Route::post('{id}/mobile/reset', [
			"as"   => "users.mobile.reset",
			"uses" => "API\InstituteUsersListAPIController@changeMobile"
		]);
	});
});
