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

Route::group(['prefix' => 'sales','middleware' => 'sentinel.auth'], function () {


	Route::get('visit/pre-sales/search', ['as' => 'sales.visit.index', 'uses' => 'VisitController@index']);
	Route::resource('visit/pre-sales', 'VisitController', [
		'except'     => ['show', 'index'],
		'names'      => [
			'create'  => 'sales.visit.create',
			'store'   => 'sales.visit.store',
			'edit'    => 'sales.visit.edit',
			'update'  => 'sales.visit.update',
			'destroy' => 'sales.visit.destroy'
		],
		'parameters' => [
			'pre-sales' => 'id'
		]
	]);
	Route::get('visit/pre-sales/{id}/acquisition', [
		'as'   => 'sales.visit.acquisition',
		'uses' => 'VisitController@acquisition'
	]);
	Route::post('visit/pre-sales/{id}/acquisition-do', [
		'as'   => 'sales.visit.acquisition-do',
		'uses' => 'VisitController@acquisitionDo'
	]);

	Route::get('visit/post-sales/search', ['as' => 'sales.post-visit.index', 'uses' => 'PostVisitController@index']);
	Route::resource('visit/post-sales', 'PostVisitController', [
		'except'     => ['show', 'index'],
		'names'      => [
			'create'  => 'sales.post-visit.create',
			'store'   => 'sales.post-visit.store',
			'edit'    => 'sales.post-visit.edit',
			'update'  => 'sales.post-visit.update',
			'destroy' => 'sales.post-visit.destroy'
		],
		'parameters' => [
			'post-sales' => 'id'
		]
	]);

	Route::match(['get', 'post'], 'report/acquisition', [
		'as'   => 'sales.report.acquisition',
		'uses' => 'AcquisitionController@report'
	]);

	Route::get('team/search', ['as' => 'sales.team.index', 'uses' => 'TeamController@index']);
	Route::resource('team', 'TeamController', [
		'except'     => ['show', 'destroy', 'index'],
		'names'      => [
			'create' => 'sales.team.create',
			'store'  => 'sales.team.store',
			'edit'   => 'sales.team.edit',
			'update' => 'sales.team.update'
		],
		'parameters' => [
			'team' => 'id'
		]
	]);

});
