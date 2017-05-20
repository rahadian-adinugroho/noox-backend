<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/news/read/{id}/{title?}', 'NewsController@read');

Route::get('fire', function(){
    event(new \Noox\Events\GenericEvent);
    return 'event fired!';
});

Route::get('private', function(){
    $comment = ['user_id' => 4, 'news_id' => 1, 'content' => 'test'];
    event(new \Noox\Events\CommentLikedEvent($comment));
    return 'comment fired!';
});
