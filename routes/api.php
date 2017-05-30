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

    $api->get('news/top_news', 'Noox\Http\Controllers\API\NewsController@getTopNews');

    $api->get('news/{id}', 'Noox\Http\Controllers\API\NewsController@details')->where('id', '[0-9]+');

    $api->get('news/{id}/comments', 'Noox\Http\Controllers\API\NewsController@getComments');

    $api->get('news/comment/{id}', 'Noox\Http\Controllers\API\NewsController@commentDetails');
});

$api->version('v1', ['middleware' => 'api.auth'], function ($api)
{
    $api->post('user/{id}/report', 'Noox\Http\Controllers\API\UserController@submitReport');

    $api->get('personal', 'Noox\Http\Controllers\API\UserController@personalDetails');

    $api->put('personal', 'Noox\Http\Controllers\API\UserController@updateProfile');

    $api->put('personal/password', 'Noox\Http\Controllers\API\UserController@updatePassword');

    $api->get('personal/comments', 'Noox\Http\Controllers\API\UserController@personalComments');

    $api->get('personal/liked_news', 'Noox\Http\Controllers\API\UserController@personalLikedNews');

    $api->get('personal/achievements', 'Noox\Http\Controllers\API\UserController@personalAchievements');

    $api->get('personal/settings', 'Noox\Http\Controllers\API\UserController@viewSettings');

    $api->get('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@viewPreferences');

    $api->post('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@updatePreferences');

    $api->get('personal/stats', 'Noox\Http\Controllers\API\UserController@personalStats');

    $api->get('news/personalised', 'Noox\Http\Controllers\API\NewsController@getPersonalisedNews');

    $api->post('news/{id}/like', 'Noox\Http\Controllers\API\NewsController@submitLike');

    $api->delete('news/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteLike');

    $api->post('news/{id}/comment', 'Noox\Http\Controllers\API\NewsController@submitComment');

    $api->post('news/comment/{id}/reply', 'Noox\Http\Controllers\API\NewsController@submitCommentReply');

    $api->post('news/comment/{id}/like', 'Noox\Http\Controllers\API\NewsController@submitCommentLike');

    $api->delete('news/comment/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteCommentLike');

    $api->post('news/comment/{id}/report', 'Noox\Http\Controllers\API\NewsController@submitNewsCommentReport');

    $api->post('news/{id}/report', 'Noox\Http\Controllers\API\NewsController@submitReport');

    $api->get('auth/renew_token', 'Noox\Http\Controllers\API\AuthController@getToken');
});