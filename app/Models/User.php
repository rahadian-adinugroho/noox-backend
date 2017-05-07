<?php

namespace Noox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $fillable = array('fb_id','google_id', 'name', 'email', 'password', 'gender', 'birthday');
    protected $hidden = array('password', 'remember_token');

    public function history()
    {
    	return $this->hasMany('Noox\Models\UserReadHistory')->orderBy('last_read', 'desc');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->orderBy('created_at', 'desc');
    }

    public function newsLikes()
    {
        return $this->hasMany('Noox\Models\NewsLike');
    }

    public function commentLikes()
    {
    	return $this->hasMany('Noox\Models\NewsCommentLike');
    }
}