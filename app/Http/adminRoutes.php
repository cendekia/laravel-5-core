<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of routes for an admin/cms.
| In order to detect route in the cms role permission feature:
| 	please always use alias name (as) on every routes (except resource route, because it automatically generated alias for you)
|
| There are 8 types of permission: index, show, create, store, edit, update, destroy, ajax
|
*/

Route::group(['middleware' => 'guest.admin'], function () {
    Route::get('admin/signin', 'Admin\AuthController@getSignIn');
    Route::post('admin/signin', 'Admin\AuthController@postSignIn');
});

Route::get('/admin', function() {
    return Redirect::to('/admin/dashboard');
});

Route::get('admin/signout', 'Admin\AuthController@getSignOut');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth.admin', 'restrictAccess', 'whitelistedIP']], function() {

	Route::group(['prefix' => 'setting', 'namespace' => 'Setting', 'middleware' => []], function() {

        Route::resource('/account', 'AccountController');

        Route::get('/members/ajax', ['uses' => 'MemberController@getData', 'as' => 'admin.setting.members.ajax']);
        Route::resource('/members', 'MemberController');

        Route::get('/roles/ajax', ['uses' => 'RoleController@getData', 'as' => 'admin.setting.roles.ajax']);
    	Route::resource('/roles', 'RoleController');

	});

    Route::resource('/dashboard', 'DashboardController');

    Route::get('/subscribes/ajax', ['uses' => 'SubscribeController@getData', 'as' => 'admin.subscribes.ajax']);
    Route::resource('/subscribes', 'SubscribeController');

    Route::resource('/media-manager', 'MediaManagerController');

});
