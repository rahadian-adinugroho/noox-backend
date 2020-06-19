<?php

namespace Noox\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = array('reporter_id', 'status_id', 'content', 'reportable_id', 'reportable_type');

    public function reportable()
    {
        return $this->morphTo();
    }

    public function status()
    {
        return $this->belongsTo('Noox\Models\ReportStatus', 'status_id');
    }

    public function reporter()
    {
        return $this->belongsTo('Noox\Models\User');
    }
}
