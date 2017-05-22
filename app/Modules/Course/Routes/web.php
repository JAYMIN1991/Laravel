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

Route::group(['prefix' => 'course', 'middleware' => 'sentinel.auth'], function () {

	/* Routes for course promotion */
	Route::get('promotion/search', ['as' => 'course.promotion.index', 'uses' => 'PromotionController@index']);
	Route::get('promotion/create-search', [
		'as'   => 'course.promotion.create-search',
		'uses' => 'PromotionController@createSearch'
	]);
	Route::get('promotion/{id}', ['as' => 'course.promotion.show', 'uses' => 'PromotionController@show']);
	Route::delete('promotion/{id}', ['as' => 'course.promotion.destroy', 'uses' => 'PromotionController@destroy']);
	Route::match(['PUT', 'PATCH'], 'promotion/{id}', [
		'as'   => 'course.promotion.store-or-update',
		'uses' => 'PromotionController@storeOrUpdate'
	]);

	/*Routes For offline payment*/
	Route::resource('offline_payment_list', 'OfflinePaymentController', [
		'except' => ['show'],
		'names'  => [
			'index'   => 'course.offline.index',
			'create'  => 'course.offline.create',
			'store'   => 'course.offline.store',
			'edit'    => 'course.offline.edit',
			'update'  => 'course.offline.update',
			'destroy' => 'course.offline.destroy'
		],

	]);
	// To Export Coupon code for perticular course
	Route::get('offline_payment_list/{id}', [
		'as'   => 'course.offline.export',
		'uses' => 'OfflinePaymentController@export'
	]);

	// Verify offline payment listing page
	Route::get('verify_offline_payment_list', [
		'as'   => 'course.verify_offline.index',
		'uses' => 'verifyOfflinePaymentController@index'
	]);

	// Generate coupon code
	// @param $id is offline_payment_id
	Route::get('verify_offline_payment_list/generate_coupon/{offline_payment_id}/{institute_id}/{course_id}/{total_buyer}', [
		'as'   => 'course.verify_offline.generate_coupon',
		'uses' => 'verifyOfflinePaymentController@generateCoupon'
	]);

	// Generate offline invoice
	Route::get('verify_offline_payment_list/mark_as_clear/{id}/{instrumentProcessStatus}', [
		'as'   => 'course.verify_offline.mark_as_clear',
		'uses' => 'verifyOfflinePaymentController@markAsClear'
	]);
});
