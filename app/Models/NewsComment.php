<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use Traits\NPerGroup;

    protected $fillable = array('news_id', 'user_id', 'content', 'parent_id');
    protected $hidden = array('pivot');

    public function author()
    {
    	return $this->belongsTo('Noox\Models\User', 'user_id');
    }

    public function news()
    {
    	return $this->belongsTo('Noox\Models\News');
    }

    public function parent()
    {
        return $this->belongsTo('Noox\Models\NewsComment', 'parent_id');
    }

    public function latestReplies()
    {
        return $this->hasMany('Noox\Models\NewsComment', 'parent_id')->latest()->nPerGroup('parent_id', 2);
    }

    public function replies()
    {
        return $this->hasMany('Noox\Models\NewsComment', 'parent_id');
    }

    public function likes()
    {
        return $this->belongsToMany('Noox\Models\User', 'news_comment_likes', 'comment_id', 'user_id');
    }

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }
}
