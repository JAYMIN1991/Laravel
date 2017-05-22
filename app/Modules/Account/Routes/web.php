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

Route::group(['prefix' => 'account',  'middleware' => 'sentinel.auth'], function () {
    Route::get('institute/banks/', ['as' => 'account.institute.bank', 'uses' => 'InstituteBankController@index']);

    Route::get('institute/commission/search/', ['as' => 'account.user-commission.search', 'uses' => 'UserCommissionListController@index']);

    Route::resource('institute/commission', 'UserCommissionListController', [
        'except'     => ['show', 'index'],
        'names'      => [
            'create'  => 'account.user-commission.create',
            'store'   => 'account.user-commission.store',
            'edit'    => 'account.user-commission.edit',
            'update'  => 'account.user-commission.update',
            'destroy' => 'account.user-commission.destroy'
        ],
    ]);
    Route::get('course/orders/', ['as' => 'account.course.orders', 'uses' => 'CourseOrdersController@index']);
});
