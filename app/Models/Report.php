<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = array('user_id', 'status_id', 'content', 'reportable_id', 'reportable_type');

    public function reportable()
    {
        return $this->morphTo();
    }

    public function status()
    {
        return $this->belongsTo('Noox\Models\ReportStatus', 'status_id');
    }

    public function submitter()
    {
        return $this->belongsTo('Noox\Models\User');
    }
}
