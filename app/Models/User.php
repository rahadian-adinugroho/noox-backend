<?php

namespace Noox\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = array(
                         'fb_id',
                         'google_id',
                         'name',
                         'email',
                         'password',
                         'gender',
                         'birthday',
                         'experience');
    protected $attributes = [
    'experience' => 0,
    ];
    protected $hidden = array('password', 'remember_token', 'pivot');

    public function achievements()
    {
        return $this->belongsToMany('Noox\Models\Achievement', 'user_achievements')->withPivot('earn_date');
    }

    public function latestAchievement()
    {
        return $this->belongsToMany('Noox\Models\Achievement', 'user_achievements')
        ->withPivot('earn_date')
        ->orderBy('pivot_earn_date', 'desc');
    }

    public function reports()
    {
        return $this->morphMany('Noox\Models\Report', 'reportable');
    }

    public function submittedReports()
    {
        return $this->hasMany('Noox\Models\Report', 'reporter_id');
    }

    public function settings()
    {
        return $this->belongsToMany('\Noox\Models\Setting', 'user_settings')->withPivot('value');
    }

    public function fcmTokens()
    {
        return $this->hasMany('Noox\Models\FcmToken');
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

    public function likedNews()
    {
        return $this->belongsToMany('Noox\Models\News', 'news_likes')->withPivot('liked_at');
    }

    public function likedComments()
    {
    	return $this->belongsToMany('Noox\Models\NewsComment', 'news_comment_likes', 'user_id', 'comment_id');
    }

    public function getSetting($key, $allowDefault = false)
    {
        if ($item = $this->settings()->where('key', $key)->first()) {
            return $item->pivot->value;
        }
        if ($allowDefault) {
            return \Noox\Models\Setting::where('key', $key)->pluck('default_value')->first();
        }
        return null;
    }

    public function getRecentNewsPreferences()
    {
        return $this->newsReadHistory()
                ->select('cat_id', \DB::raw("COUNT(*) as 'read_count'"))
                ->where('last_read', '>', \Carbon\Carbon::now()->subWeeks(2))
                ->having('read_count', '>', 0)
                ->groupBy('cat_id')
                ->orderBy('read_count', 'desc')
                ->get();
    }

    public function getLevel()
    {
        $exp = $this->experience;
        for ($level = 1; (pow($level, 3) + $level - 2) <= $exp ; $level++){}
        return $level-1;
    }

    public function getStats()
    {
        $user_data = [
        'id'         => $this->id,
        'name'       => $this->name,
        'created'    => $this->created_at->format('Y-m-d H:i:s'),
        'experience' => $this->experience,
        ];

        // use subquery for database adaptability
        $subquery = DB::table('news')
        ->select('cat_id', DB::raw('COUNT(*) as `read_count`'))
        ->join('user_read_history', 'user_read_history.news_id' , '=', 'news.id')
        ->where('user_read_history.user_id', $this->id)
        ->groupBy('cat_id');

        $nrc_data = DB::table('news_categories')
        ->select('name', 't.read_count')
        ->leftJoin(DB::raw("({$subquery->toSql()}) as `t`"), 't.cat_id', '=', 'news_categories.id')
        ->mergeBindings($subquery)
        ->get();

        $news_read_count = array();
        foreach ($nrc_data as $key => $data) {
            $news_read_count[$data->name] = $data->read_count;
        }

        $news_likes_count = $this->likedNews()->count();

        $comment_count = $this->comments()->count();

        $liked_comments_count = $this->comments()->has('likers', '>=', 1)->count();

        $comment_likes_count = $this->likedComments()->count();

        $report_count = $this->submittedReports()->count();

        $news_report_count = $this->submittedReports()->where('reportable_type', 'news')->count();

        //check db seed to get an idea about where this value came from
        $approved_report_count = $this->submittedReports()->where('status_id', 4)->count();

        $approved_news_report_count = $this->submittedReports()->where('reportable_type', 'news')->where('status_id', 4)->count();

        return compact(
            'user_data', 
            'news_likes_count',
            'comment_count',
            'comment_likes_count',
            'liked_comments_count',
            'report_count',
            'news_report_count',
            'approved_report_count',
            'approved_news_report_count',
            'news_read_count');
    }
}
