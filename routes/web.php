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

Route::get('/insert', function() {
	Noox\Models\User::create(['name'=>'Jalil Master','email'=>'jalilmaster@gmail.com','password'=>'k123k12ndn2']);
	return 'Biodata Created';
});

Route::get('/update', function() {
	$user = Noox\Models\User::find(1);
	$user->name = "Jalil Boy";
	$user->save();
	return 'Biodata Updated';
});

Route::get('/news/category/{key}', function($key) {
	$list = Noox\Models\NewsCategory::with('news', 'news.source')->where('name', $key)->paginate(15);

	return (is_null($list)) ? '404' : $list ;
});

Route::get('/categories', function() {
	$categories = Noox\Models\NewsCategory::orderBy('name', 'asc')->get();

	return (is_null($categories)) ? '404' : $categories ;
});

Route::get('/news/{id}', function($id) {
	$news = Noox\Models\News::with(['comments' => function($query){
		$query->select('id', 'news_id', 'created_at', 'content')->whereNull('parent_id')->take(5);
	}, 'comments.replies' => function($query){
		$query->select('id', 'news_id', 'created_at', 'content', 'parent_id')->take(2);
	}, 'source', 'category'])->find($id);

	return (is_null($news)) ? '404' : $news ;
});

Route::get('/read/{id}', function($id) {
	$news = Noox\Models\News::with([
	'comments' => function($query)
	{
		$query->select('id', 'user_id', 'news_id', 'created_at', 'content')->withCount('replies', 'likes')->whereNull('parent_id')->take(10);
	},
	'comments.author' => function ($query) 
	{
		$query->select('id', 'name');
	},
	'comments.replies' => function($query)
	{
		$query->select('id', 'user_id', 'news_id', 'created_at', 'content', 'parent_id')->withCount('likes')->take(2);
	},
	'comments.replies.author' => function ($query) 
	{
		$query->select('id', 'name');
	}
	, 'source', 'category'])->withCount('likes')->find($id);

	if(is_null($news))
	{
		return '404';
	}
	else
	{
		$existing = Noox\Models\UserReadHistory::where('user_id', 1)->where('news_id', $id)->first();
		if(is_null($existing))
		{
			$history = new Noox\Models\UserReadHistory();
			$history->user_id = 1;
			$history->news_id = $id;

			$history->save();
		}
		else
		{
			$existing->touch();
		}
		return $news;
	}

});

Route::get('/comment_insert', function() {
	$comment = new Noox\Models\NewsComment();
	$comment->news_id = 1;
	$comment->content = "Bubarin aja ormas kaya gini.";
	$user = Noox\Models\User::find(1);
	$user->comments()->save($comment);
	return 'Comment Inserted';
});

Route::get('/comment_get', function() {
	$comments = Noox\Models\NewsComment::with([
		'author' => function ($query)
		{
			$query->select('id', 'name');
		},
		'replies' => function ($query)
		{
			$query->select('id', 'user_id', 'content', 'parent_id')->withCount('likes');
		},
		'replies.author'=> function ($query)
		{
			$query->select('id', 'name');
		}
		])->select('id','user_id', 'created_at', 'content')->withCount(['likes', 'replies'])->find(2);
	return $comments;
});

Route::get('/user/{id}', function($id) {
	$user = Noox\Models\User::with([
		'comments' => function($query)
		{
			$query->select('user_id', 'news_id', 'created_at', 'content')->whereNull('parent_id');
		},
		'comments.news' => function($query)
		{
			$query->select('id', 'title');
		}
		])->select('id', 'name', 'created_at as member_since')->withCount([
		'comments' => function($query)
		{
			$query->whereNull('parent_id');
		}
		])->find($id);
	if(!is_null($user))
	{
		$ret = ['user' => $user];
		return $ret;
	}
	else
	{
		return '404';
	}
	
});

Route::get('/user_like', function() {
	$count = Noox\Models\User::select('id', 'name')->with('newsLikes.news')->withCount(['newsLikes', 'commentLikes'])->find(1);
	return $count;
});

Route::get('/news_like', function() {
	$existing = Noox\Models\NewsLike::where('user_id', 1)->where('news_id', 1)->first();
	if(is_null($existing))
	{
		$like = new Noox\Models\NewsLike();
		$like->user_id = 1;
		$like->news_id = 1;
		$like->save();
		$res = $like;
	}
	else
	{
		$res = ['error' => 'code', 'msg' => 'Already like this news.'];
	}
	return $res;
});

Route::get('/comment_like', function() {
	$existing = Noox\Models\NewsCommentLike::where('user_id', 1)->where('comment_id', 2)->first();
	if(is_null($existing))
	{
		$like = new Noox\Models\NewsCommentLike();
		$like->user_id = 1;
		$like->comment_id = 2;
		$like->save();
		$res = $like;
	}
	else
	{
		$res = ['error' => 'code', 'msg' => 'Already like this comment.'];
	}
	return $res;
});