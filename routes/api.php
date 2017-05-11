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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) 
{
    $api->post('auth/login', 'Noox\Http\Controllers\API\AuthController@authenticate');

    $api->post('auth/logout', 'Noox\Http\Controllers\API\AuthController@logout');

    $api->post('users', 'Noox\Http\Controllers\API\UserController@register');

    $api->get('user/{id}', 'Noox\Http\Controllers\API\UserController@details');

    $api->get('news/{id}', 'Noox\Http\Controllers\API\NewsController@details');

    $api->get('news/{id}/comments', 'Noox\Http\Controllers\API\NewsController@getComments');

    $api->get('news/comment/{id}', 'Noox\Http\Controllers\API\NewsController@commentDetails');
});

$api->version('v1', ['middleware' => 'api.auth'], function ($api)
{
    $api->post('news/{id}/comment', 'Noox\Http\Controllers\API\NewsController@submitComment');

    $api->post('news/comment/{id}/reply', 'Noox\Http\Controllers\API\NewsController@submitCommentReply');

    $api->get('auth/renew_token', 'Noox\Http\Controllers\API\AuthController@getToken');
});