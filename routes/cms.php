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

Route::get('/admin/{id?}', 'CMS\AdminController@profile')->name('admin.profile');

Route::get('/admins', 'CMS\AdminController@adminList')->name('admins');

Route::get('/admin/create', 'CMS\AdminController@viewCreate')->name('admin.create');

Route::post('/admin/create', 'CMS\AdminController@create')->name('admin.create.submit');

Route::get('/users', 'CMS\UserController@index')->name('cms.users');

Route::get('/users/reported', 'CMS\UserController@reported')->name('cms.users.reported');

Route::get('/users/ranking', 'CMS\UserController@ranking')->name('cms.users.ranking');

Route::get('/user/{id}', 'CMS\UserController@view')->name('cms.user.profile');

Route::get('/user/{id}/reports', 'CMS\UserController@userReports')->name('cms.user.reports');

Route::get('/news', 'CMS\NewsController@index')->name('cms.news');

Route::get('/news/reported', 'CMS\NewsController@reported')->name('cms.news.reported');

Route::get('/news/deleted', 'CMS\NewsController@deleted')->name('cms.news.deleted');

Route::get('/news/{id}', 'CMS\NewsController@view')->name('cms.news.details');

Route::get('/news/{id}/reports', 'CMS\NewsController@newsReports')->name('cms.news.reports');

Route::get('/news/comments/reported', 'CMS\NewsController@reportedComments')->name('cms.news.comments.reported');

Route::get('/news/comment/{id}', 'CMS\NewsController@viewComment')->name('cms.news.comment.details');

Route::get('/news/comment/{id}/reports', 'CMS\NewsController@viewCommentReports')->name('cms.news.comment.reports');

Route::post('/news/comment/{id}/delete', 'CMS\NewsController@permanentDeleteComment')->name('cms.news.comment.delete');

Route::get('/reports', 'CMS\ReportController@index')->name('cms.reports');

Route::get('/reports/user', 'CMS\UserController@reported')->name('cms.reports.user');

Route::get('/reports/news', 'CMS\NewsController@reported')->name('cms.reports.news');

Route::get('/report/{id}', 'CMS\ReportController@view')->name('cms.report.details');
