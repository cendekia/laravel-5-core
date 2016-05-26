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
    $defaultRouteName = 'admin..index';

    Route::resource('/dashboard', 'DashboardController');
});
