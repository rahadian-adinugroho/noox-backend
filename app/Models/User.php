<?php

namespace Noox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $fillable = array('fb_id','google_id', 'name', 'email', 'password', 'gender', 'birthday', 'level', 'xp');
    protected $attributes = [
    'level' => 1,
    'xp'    => 0,
    ];
    protected $hidden = array('password', 'remember_token');

    public function newsReadHistory()
    {
    	return $this->belongsToMany('Noox\Models\News', 'user_read_history')->withTimestamps('first_read', 'last_read')->orderBy('last_read', 'desc');
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
