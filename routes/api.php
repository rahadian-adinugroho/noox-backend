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
});

$api->version('v1', ['middleware' => 'api.auth'], function ($api)
{
    $api->get('auth/renew_token', 'Noox\Http\Controllers\API\AuthController@getToken');
});