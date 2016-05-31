<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an admin/cms.
| In order to detect route in the cms role permission feature:
| 	please always use alias name (as) on every routes (except resource route, because it automatically generate alias)
|
| There are 4 types of permission: index, store, destroy, update
| Example:
|
|
*/

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => []], function() {

	Route::group(['prefix' => 'setting', 'namespace' => 'Setting', 'middleware' => []], function() {

    	Route::resource('/account', 'AccountController');
    	Route::resource('/roles', 'RoleController');

	});

	Route::get('/', function() {
	    return Redirect::to('/admin/dashboard');
	});

    Route::resource('/dashboard', 'DashboardController');

});
