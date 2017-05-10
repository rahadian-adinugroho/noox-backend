<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLike extends Model
{
    use Traits\HasCompositePrimaryKey;

    protected $primaryKey = array('news_id', 'user_id');
    public $incrementing  = false;
	public $timestamps    = false;

	public function owner()
    {
    	return $this->belongsTo('Noox\Models\User');
    }

    public function news($value='')
    {
    	return $this->belongsTo('Noox\Models\News');
    }
}
