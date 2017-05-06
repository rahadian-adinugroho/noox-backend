<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public $timestamps  = false;
    
    protected $table    = 'news';
    protected $fillable = array('source_id','cat_id', 'title', 'pubtime', 'author', 'content');

    public function source()
    {
    	return $this->belongsTo('Noox\Models\NewsSource', 'source_id');
    }

    public function category()
    {
    	return $this->belongsTo('Noox\Models\NewsCategory', 'cat_id');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->orderBy('created_at', 'desc');
    }

    public function likes()
    {
        return $this->hasMany('Noox\Models\NewsLike');
    }
}
