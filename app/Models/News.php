<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    
    public $timestamps  = false;
    
    protected $dates    = ['deleted_at'];
    protected $fillable = array('source_id','cat_id', 'title', 'pubtime', 'author', 'content');
    protected $hidden   = ['pivot'];

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
        return $this->belongsToMany('Noox\Models\User', 'user_read_history')->withTimestamps('first_read', 'last_read');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->where('parent_id', null)->orderBy('created_at', 'desc');
    }

    public function likes()
    {
        return $this->belongsToMany('Noox\Models\User', 'news_likes');
    }
}
