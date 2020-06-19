<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
	public $timestamps  = false;

	protected $fillable = array('source_name', 'base_url');

    public function news()
    {
    	return $this->hasMany('Noox\Models\News', 'source_id')->orderBy('pubtime', 'desc');
    }
}
