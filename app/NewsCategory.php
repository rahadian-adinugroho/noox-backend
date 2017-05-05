<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $table = 'news_category';
    protected $fillable = array('name');

    public function news()
    {
    	return $this->hasMany('App\News', 'cat_id');
    }
}
