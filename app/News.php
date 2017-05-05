<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = array('source_id','cat_id', 'title', 'pubtime', 'author', 'content');

    public function source()
    {
    	return $this->belongsTo('App\NewsSource', 'source_id');
    }

    public function category()
    {
    	return $this->belongsTo('App\NewsCategory', 'cat_id');
    }

    public function comments()
    {
    	return $this->hasMany('App\NewsComment')->orderBy('created_at', 'desc');
    }
}
