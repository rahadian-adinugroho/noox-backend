<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = array('fb_id','google_id', 'name', 'email', 'password', 'gender', 'birthday');

    public function comments()
    {
    	return $this->hasMany('App\NewsComment')->orderBy('created_at', 'desc');
    }

    public function likes()
    {
    	return $this->hasMany('App\NewsLike');
    }
}
