<?php

namespace Noox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = array('name', 'email', 'password', 'api_token');
    protected $hidden   = array('password', 'remember_token', 'api_token', 'role');
}
