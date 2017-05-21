<?php

namespace Noox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = array('fb_id','google_id', 'name', 'email', 'password', 'gender', 'birthday', 'level', 'xp');
    protected $attributes = [
    'level' => 1,
    'xp'    => 0,
    ];
    protected $hidden = array('password', 'remember_token', 'pivot');

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }

    public function submittedReports()
    {
        return $this->hasMany('Noox\Models\Report');
    }

    public function newsPreferences()
    {
        return $this->belongsToMany('Noox\Models\NewsCategory', 'user_news_preferences', 'user_id', 'category_id');
    }

    public function newsReadHistory()
    {
    	return $this->belongsToMany('Noox\Models\News', 'user_read_history')->withTimestamps('first_read', 'last_read');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment');
    }

    public function newsLikes()
    {
        return $this->belongsToMany('Noox\Models\News', 'news_likes');
    }

    public function commentLikes()
    {
    	return $this->belongsToMany('Noox\Models\NewsComment', 'news_comment_likes', 'user_id', 'comment_id');
    }

    public function getStats()
    {
        $user_data = [
        'id'      => $this->id,
        'name'    => $this->name,
        'created' => $this->created_at->format('Y-m-d H:i:s'),
        'level'   => $this->level,
        'xp'      => $this->xp,
        ];

        // use subquery for database adaptability
        $subquery = DB::table('news')
        ->select('cat_id', DB::raw('COUNT(*) as `read_count`'))
        ->join('user_read_history', 'user_read_history.news_id' , '=', 'news.id')
        ->where('user_read_history.user_id', $this->id)
        ->groupBy('cat_id');

        $news_read_count = DB::table('news_categories')
        ->select('name', 't.read_count')
        ->leftJoin(DB::raw("({$subquery->toSql()}) as `t`"), 't.cat_id', '=', 'news_categories.id')
        ->mergeBindings($subquery)
        ->get();

        $news_read_count = array_map(function($data){
            return [$data->name => ($data->read_count) ?: 0];
        }, $news_read_count->toArray());

        $news_likes_count = $this->newsLikes()->count();

        $comment_count = $this->comments()->count();

        $liked_comments_count = $this->comments()->has('likes', '>=', 1)->count();

        $comment_likes_count = $this->commentLikes()->count();

        $report_count = $this->submittedReports()->count();

        //check db seed to get an idea about where this value came from
        $approved_report_count = $this->submittedReports()->where('status_id', 4)->count();
        
        return compact(
            'user_data', 
            'news_likes_count',
            'comment_count',
            'comment_likes_count',
            'liked_comments_count',
            'report_count',
            'approved_report_count',
            'news_read_count');
    }
}
