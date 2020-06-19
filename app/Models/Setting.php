<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps  = false;
    
    protected $fillable = array('key', 'default_value');
    protected $hidden   = array('pivot');

    public function users()
    {
        return $this->belongsToMany('Noox\Models\User', 'user_settings');
    }
}
