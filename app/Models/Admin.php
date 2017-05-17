<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = array('name', 'email', 'password', 'api_token');
    protected $hidden   = array('password', 'remember_token', 'api_token', 'role');
}
