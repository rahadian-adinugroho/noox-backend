<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCommentLike extends Model
{
    use Traits\HasCompositePrimaryKey;

	protected $primaryKey = array('comment_id', 'user_id');
	public $incrementing  = false;
	public $timestamps    = false;

    public function owner()
    {
    	return $this->belongsTo('Noox\Models\User');
    }

    public function comment($value='')
    {
    	return $this->belongsTo('Noox\Models\NewsComment', 'comment_id');
    }
}
