<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $timestamps  = false;
    
    protected $table    = 'news';
    protected $fillable = array('source_id','cat_id', 'title', 'pubtime', 'author', 'content');

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }

    public function source()
    {
    	return $this->belongsTo('Noox\Models\NewsSource', 'source_id');
    }

    public function category()
    {
    	return $this->belongsTo('Noox\Models\NewsCategory', 'cat_id');
    }

    public function readers()
    {
        return $this->belongsToMany('Noox\Models\User', 'user_read_history')->withTimestamps('first_read', 'last_read')->orderBy('last_read', 'desc');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->where('parent_id', null)->orderBy('created_at', 'desc');
    }

    public function likes()
    {
        return $this->hasMany('Noox\Models\NewsLike');
    }
}
