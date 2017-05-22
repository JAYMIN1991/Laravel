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

Route::group(['prefix' => 'utility'], function () {

	Route::get('/change-password/', ['as'   => 'utility.changePassword.index',
	                                 'uses' => 'ChangePasswordController@index'
	]);
	Route::post('/change-password/update/', ['as'   => 'utility.changePassword.updatePassword',
	                                         'uses' => 'ChangePasswordController@updatePassword'
	]);
});
