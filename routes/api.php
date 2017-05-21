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
    $api->post('user/{id}/report', 'Noox\Http\Controllers\API\UserController@submitReport');

    $api->get('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@viewPreferences');

    $api->post('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@updatePreferences');

    $api->post('news/{id}/like', 'Noox\Http\Controllers\API\NewsController@submitLike');

    $api->delete('news/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteLike');

    $api->post('news/{id}/comment', 'Noox\Http\Controllers\API\NewsController@submitComment');

    $api->post('news/comment/{id}/reply', 'Noox\Http\Controllers\API\NewsController@submitCommentReply');

    $api->post('news/comment/{id}/like', 'Noox\Http\Controllers\API\NewsController@submitCommentLike');

    $api->delete('news/comment/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteCommentLike');

    $api->post('news/{id}/report', 'Noox\Http\Controllers\API\NewsController@submitReport');

    $api->get('auth/renew_token', 'Noox\Http\Controllers\API\AuthController@getToken');
});