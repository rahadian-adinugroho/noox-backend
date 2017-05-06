<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    protected $table = 'news_comment';
    protected $fillable = array('news_id', 'user_id', 'content', 'parent_id');

    public function author()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function news()
    {
    	return $this->belongsTo('App\News');
    }

    public function parent()
    {
        return $this->belongsTo('App\NewsComment', 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany('App\NewsComment', 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany('App\NewsLike', 'comment_id');
    }
}
