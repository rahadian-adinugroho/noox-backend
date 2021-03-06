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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) 
{
    $api->post('auth/login', 'Noox\Http\Controllers\API\AuthController@authenticate');

    $api->get('auth/renew_token', 'Noox\Http\Controllers\API\AuthController@getToken');

    $api->post('auth/logout', 'Noox\Http\Controllers\API\AuthController@logout');

    $api->post('users', 'Noox\Http\Controllers\API\UserController@register');

    $api->get('user/{id}', 'Noox\Http\Controllers\API\UserController@details');

    $api->get('user/{id}/comments', 'Noox\Http\Controllers\API\UserController@comments');

    $api->get('news/top_news', 'Noox\Http\Controllers\API\NewsController@getTopNews');

    $api->get('news/category/{category}', 'Noox\Http\Controllers\API\NewsController@getByCategory');

    $api->get('news/{id}', 'Noox\Http\Controllers\API\NewsController@details')->where('id', '[0-9]+');

    $api->get('news/{id}/comments', 'Noox\Http\Controllers\API\NewsController@getComments')->where('id', '[0-9]+');

    $api->get('news/comment/{id}', 'Noox\Http\Controllers\API\NewsController@commentDetails');

    $api->get('news/comment/{id}/replies', 'Noox\Http\Controllers\API\NewsController@commentReplies');

    $api->get('news/search', 'Noox\Http\Controllers\API\NewsController@search');

    $api->post('news/analyze', 'Noox\Http\Controllers\API\NewsAnalyzerController@analyze');
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

    $api->post('personal/achievements', 'Noox\Http\Controllers\API\UserController@addAchievement');

    $api->get('personal/settings', 'Noox\Http\Controllers\API\UserController@viewSettings');

    $api->put('personal/settings', 'Noox\Http\Controllers\API\UserController@updateSettings');

    $api->post('personal/fcm_token', 'Noox\Http\Controllers\API\UserController@addFcmToken');

    $api->get('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@viewPreferences');

    $api->post('personal/news_preferences', 'Noox\Http\Controllers\API\UserController@updatePreferences');

    $api->get('personal/stats', 'Noox\Http\Controllers\API\UserController@personalStats');

    $api->get('news/personalised', 'Noox\Http\Controllers\API\NewsController@getPersonalisedNews');

    $api->post('news/{id}/like', [
        'uses' => 'Noox\Http\Controllers\API\NewsController@submitLike',
        'controller' => 'Noox\Http\Controllers\API\NewsController@submitLike',
        'middleware' => 'api.throttle',
        'limit' => 20,
        'expires' => 1, // in minutes
    ]);

    $api->delete('news/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteLike');

    $api->post('news/{id}/comment', 'Noox\Http\Controllers\API\NewsController@submitComment');

    $api->post('news/comment/{id}/reply', 'Noox\Http\Controllers\API\NewsController@submitCommentReply');

    $api->post('news/comment/{id}/like', [
        'uses' => 'Noox\Http\Controllers\API\NewsController@submitCommentLike',
        'controller' => 'Noox\Http\Controllers\API\NewsController@submitCommentLike',
        'middleware' => 'api.throttle',
        'limit' => 20,
        'expires' => 1, // in minutes
    ]);

    $api->delete('news/comment/{id}/like', 'Noox\Http\Controllers\API\NewsController@deleteCommentLike');

    $api->post('news/comment/{id}/report', 'Noox\Http\Controllers\API\NewsController@submitNewsCommentReport');

    $api->post('news/{id}/report', 'Noox\Http\Controllers\API\NewsController@submitReport');
});