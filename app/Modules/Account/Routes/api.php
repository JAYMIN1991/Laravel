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

Route::group(['prefix' => 'v1.0/account', 'middleware' => ['api.auth']], function () {

	Route::get('mark-as-paid/{trans_id}', [
		'uses' => 'API\CourseOrdersAPIController@markAsPaid',
		'as'   => 'api.account.mark-as-paid'
	])->where('trans_id', '[0-9]+');

	Route::get('generate-invoice/',[
		'uses' => 'API\CourseOrdersAPIController@generateInvoice',
		'as'    =>  'api.account.generate-invoice'
	]);
});