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

Route::get('/insert', function() {
	App\User::create(['name'=>'Jalil Master','email'=>'jalilmaster@gmail.com','password'=>'k123k12ndn2']);
	return 'Biodata Created';
});

Route::get('/update', function() {
	$user = App\User::find(1);
	$user->name = "Jalil Boy";
	$user->save();
	return 'Biodata Updated';
});

Route::get('/news/category/{key}', function($key) {
	$list = App\NewsCategory::with('news', 'news.source')->where('name', $key)->paginate(15);

	return (is_null($list)) ? '404' : $list ;
});

Route::get('/categories', function() {
	$categories = App\NewsCategory::orderBy('name', 'asc')->get();

	return (is_null($categories)) ? '404' : $categories ;
});

Route::get('/news/{id}', function($id) {
	$news = App\News::with(['comments' => function($query){
		$query->select('id', 'news_id', 'created_at', 'content')->whereNull('parent_id')->take(5);
	}, 'comments.replies' => function($query){
		$query->select('id', 'news_id', 'created_at', 'content', 'parent_id')->take(2);
	}, 'source', 'category'])->find($id);

	return (is_null($news)) ? '404' : $news ;
});

Route::get('/comment_insert', function() {
	$comment = new App\NewsComment();
	$comment->news_id = 1;
	$comment->content = "Bubarin aja ormas kaya gini.";
	$user = App\User::find(1);
	$user->comments()->save($comment);
	return 'Comment Inserted';
});

Route::get('/comment_get', function() {
	$comments = App\NewsComment::with([
		'author' => function ($query) {
			$query->select('id', 'name');
		},
		'news' => function ($query) {
			$query->select('id', 'title');
		}
		])->select('user_id', 'news_id', 'id as comment_id', 'created_at', 'content')->find(2);
	return $comments;
});