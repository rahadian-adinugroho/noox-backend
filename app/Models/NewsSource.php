<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
	public $timestamps  = false;

	protected $table    = 'news_source';
	protected $fillable = array('source_name', 'base_url');

    public function news()
    {
    	return $this->hasMany('Noox\Models\News', 'source_id')->orderBy('created_at', 'desc');
    }
}