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
Route::group(['prefix' => 'v1.0/sales'], function () {

	/* Route for auto suggest */
	Route::group(['prefix' => 'suggest'], function () {

		/* Route to get cities from institute inquiry */
		Route::get('city/', ['uses' => 'SalesApiController@getAvailableCities', 'as' => 'api.sales.city']);

		/* Route to get designation from institute inquiry */
		Route::get('designation/', [
			'uses' => 'SalesApiController@getAvailableDesignations',
			'as'   => 'api.sales.designation'
		]);

		/* Route to get designation from after sales visit */
		Route::get('post-visit/designation/', [
			'uses' => 'SalesApiController@getAvailableDesignationsForAfterSalesVisit',
			'as'   => 'api.sales.post-visit.designation'
		]);

	});

	/* Route to get non acquired institute detail */
	Route::get('not-acquired-institute/{id}', [
		'uses' => 'SalesApiController@getNotAcquiredInstitute',
		'as'   => 'api.sales.not-acquired-institute'
	])->where('id', '[0-9]+');

	/* Route to get last after sales visit detail of institute */
	Route::get('get-last-post-visit-of-institute/{id}', [
		'uses' => 'SalesApiController@getLastAfterSaleVisitOfInstitute',
	    'as' => 'api.sales.last-post-visit-of-institute'
	]);
});