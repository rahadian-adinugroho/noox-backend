<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users', 'CMS\API\UserController@index');

Route::get('/users/reported', 'CMS\API\UserController@reported');

Route::get('/users/ranking', 'CMS\API\UserController@ranking');

Route::get('/news', 'CMS\API\NewsController@index');

Route::get('/news/reported', 'CMS\API\NewsController@reported');

Route::get('/news/deleted', 'CMS\API\NewsController@deleted');

Route::get('/reports', 'CMS\API\ReportController@index');
