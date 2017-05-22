<?php

/*
|--------------------------------------------------------------------------
| Cms Routes
|--------------------------------------------------------------------------
|
| List of routes for cms.
|
*/

Route::get('/', 'CMS\AdminController@index')->name('admin.dashboard');

Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');

Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');

Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout.submit');

Route::get('/admin/profile/{id?}', 'CMS\AdminController@profile')->name('admin.profile');

Route::get('/users', 'CMS\UserController@index')->name('cms.users');

Route::get('/users/reported', 'CMS\UserController@reported')->name('cms.users.reported');

Route::get('/users/ranking', 'CMS\UserController@ranking')->name('cms.users.ranking');

Route::get('/user/{id}', 'CMS\UserController@view')->name('cms.user.profile');

Route::get('/user/{id}/reports', 'CMS\UserController@userReports')->name('cms.user.reports');
