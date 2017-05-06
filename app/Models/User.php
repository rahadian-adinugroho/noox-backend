<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = array('fb_id','google_id', 'name', 'email', 'password', 'gender', 'birthday');

    public function history()
    {
    	return $this->hasMany('Noox\Models\UserReadHistory')->orderBy('last_read', 'desc');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->orderBy('created_at', 'desc');
    }

    public function commentLikes()
    {
    	return $this->hasMany('Noox\Models\NewsCommentLike');
    }
}
