<?php

namespace Noox\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Noox\Models\NewsCategory;

class News extends Model
{
    use SoftDeletes;

    public $timestamps  = false;

    protected $dates    = ['deleted_at'];
    protected $fillable = array('source_id','cat_id', 'title', 'pubtime', 'author', 'content');
    protected $hidden   = ['pivot'];

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }

    public function source()
    {
    	return $this->belongsTo('Noox\Models\NewsSource', 'source_id');
    }

    public function category()
    {
    	return $this->belongsTo('Noox\Models\NewsCategory', 'cat_id');
    }

    public function readers()
    {
        return $this->belongsToMany('Noox\Models\User', 'user_read_history')->withTimestamps('first_read', 'last_read');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment')->orderBy('created_at', 'desc');
    }

    public function likers()
    {
        return $this->belongsToMany('Noox\Models\User', 'news_likes')->withPivot('liked_at');
    }

    public function getCategoryName()
    {
        $catId = $this->cat_id;

        $catName = null;
        if (Cache::has('news_categories')) {

            $categories = Cache::get('news_categories');

        } else {
            $res = NewsCategory::all();

            $categories = array();
            foreach ($res as $key => $data) {
                $categories[$data->id] = $data->name;
            }
            Cache::forever('news_categories', $categories);
        }

        if (isset($categories[$catId])) {
            $catName = $categories[$catId];
        }
        return $catName;
    }
}
