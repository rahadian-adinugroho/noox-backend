<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsLike extends Model
{
	protected $primaryKey = null;
	public $incrementing  = false;
	public $timestamps    = false;

	protected $table      = 'news_comment_like';
	protected $fillable   = array('comment_id', 'user_id');

    public function owner()
    {
    	return $this->belongsTo('App\User');
    }

    public function comment($value='')
    {
    	return $this->belongsTo('App\NewsComment', 'comment_id');
    }
}
