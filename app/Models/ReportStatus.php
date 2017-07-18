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

    /**
     * Get ID of given status name.
     *
     * @param  string $name
     * @return int
     */
    public static function getId($name)
    {
        if (\Cache::has('report_statuses')) {

            $statuses = \Cache::get('report_statuses');

        } else {
            $res = self::all();

            $statuses = array();
            foreach ($res as $key => $data) {
                $statuses[$data->name] = $data->id;
            }
            \Cache::forever('report_statuses', $statuses);
        }

        if (isset($statuses[$name])) {
            return $statuses[$name];
        } else {
            return null;
        }
    }
}
