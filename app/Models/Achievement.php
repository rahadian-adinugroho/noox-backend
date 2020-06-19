<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = array('key', 'title', 'description', 'xpbonus');

    protected $hidden = array('pivot');

    public function achievers()
    {
        return $this->belongsToMany('Noox\Models\User', 'user_achievements')->withPivot('earn_date');
    }
}
