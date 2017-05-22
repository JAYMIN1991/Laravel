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

Route::group(['prefix' => 'v1.0/course', 'middleware' => ['api.auth']], function () {

	/*Generate Offline payment API*/
	// @param $id is offline_payment_id
	Route::get('generate_offline_invoice/{id}/{user_id}/{to_do}', [
		'as'   => 'api.course.generate_offline_invoice',
		'uses' => 'API\verifyOfflinePaymentAPIController@generateOfflinePaymentInvoice'
	]);

	// Mark return or cancel API call route
	Route::get('mark_return_cancel',[
		'as' => 'api.course.mark_return_cancel',
	    'uses' => 'API\verifyOfflinePaymentAPIController@markReturnOrCancel'
	]);
});
