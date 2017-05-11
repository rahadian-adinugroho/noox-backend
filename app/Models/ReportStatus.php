<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class ReportStatus extends Model
{
    public $timestamps  = false;

    protected $fillable = array('name');

    public function reports()
    {
        return $this->hasMany('Noox\Models\Report', 'status_id');
    }
}
