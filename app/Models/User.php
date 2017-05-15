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
    protected $hidden = array('password', 'remember_token');

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }

    public function newsReadHistory()
    {
    	return $this->belongsToMany('Noox\Models\News', 'user_read_history')->withTimestamps('first_read', 'last_read')->orderBy('last_read', 'desc');
    }

    public function comments()
    {
    	return $this->hasMany('Noox\Models\NewsComment');
    }

    public function newsLikes()
    {
        return $this->hasMany('Noox\Models\NewsLike');
    }

    public function commentLikes()
    {
    	return $this->hasMany('Noox\Models\NewsCommentLike');
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

        // $news_read_count->map(function($data){
        //     return [$data->name => ($data->read_count) ?: 0];
        // });
        // $news_read_count = DB::select('SELECT `name`, `t`.`read_count` FROM `news_categories` LEFT JOIN (SELECT `cat_id`, COUNT(*) AS `read_count` FROM `news` JOIN `user_read_history` ON `user_read_history`.`news_id` = `news`.`id` AND `user_read_history`.`user_id` = ? GROUP BY `cat_id`) AS `t` ON `t`.`cat_id` = `news_categories`.`id`', [$user_id]);

        $news_read_count = array_map(function($data){
            return [$data->name => ($data->read_count) ?: 0];
        }, $news_read_count->toArray());

        $news_likes_count = $this->newsLikes()->count();

        $comment_count = $this->comments()->count();

        $comment_likes_count = $this->commentLikes()->count();

        return compact('user_data', 'news_likes_count', 'comment_count', 'comment_likes_count', 'news_read_count');
    }
}
