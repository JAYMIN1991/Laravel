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

Route::get('/report', function (Request $request) {
})->middleware('auth:api');


Route::group(['prefix' => 'v1.0', 'middleware' => ['decrypt.id', 'api.auth']], function () {

	Route::group(['prefix' => 'report'], function () {

		// Password reset API for user search page.
		Route::get('institute-inquiry/{id}/', [
			"as"   => "api.report.institute-inquiry",
			"uses" => "API\InstituteListAPIController@getInstituteInquiryDetails"
		]);

		Route::post('institute-inquiry/{id}/edit/', [
			"as"   => "api.report.institute-inquiry.edit",
			"uses" => "API\InstituteListAPIController@editInstituteInquiryDetails"
		]);

		Route::post('plan/activate/{id}/', [
			"as"   => "api.report.plan.activate",
			"uses" => "API\InstituteListAPIController@activatePlan"
		]);

		Route::post('plan/deactivate/{id}/', [
			"as"   => "api.report.plan.deactivate",
			"uses" => "API\InstituteListAPIController@deactivatePlan"
		]);

		Route::post('plan/verify/{id}/', [
			"as"   => "api.report.plan.verify",
			"uses" => "API\InstituteListAPIController@deactivatePlan"
		]);

		Route::post('plan/cancel/{id}/', [
			"as"   => "api.report.plan.cancel",
			"uses" => "API\InstituteListAPIController@cancelPlan"
		]);
	});
});
