<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
	public $timestamps  = false;
	
	protected $table    = 'news_category';
	protected $fillable = array('name');

    public function news()
    {
    	return $this->hasMany('Noox\Models\News', 'cat_id');
    }
}
