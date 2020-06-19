<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    public $timestamps  = false;

    protected $fillable = array('token');

    public function user()
    {
        return $this->belongsTo('Noox\Models\User');
    }
}
