<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $table = 'news_source';
    protected $fillable = array('source_name', 'base_url');

    public function news()
    {
    	return $this->hasMany('App\News', 'source_id')->orderBy('created_at', 'desc');
    }
}
