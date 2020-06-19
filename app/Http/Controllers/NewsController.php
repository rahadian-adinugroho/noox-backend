<?php

namespace Noox\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Noox\Models\News;
use Cache;

class NewsController extends Controller
{
    /**
     * View news article.
     * Take news id from URL, and display the news content if available. When news not found it will return 404 error.
     * 
     * @param  integer $newsId
     * @param  string $title
     * 
     * @return \Illuminate\Http\Response
     */
    public function read($newsId, $title = null)
    {
        if (! $news = News::with(['category', 'source'])->find($newsId)) {
            abort(404, 'Artikel tidak ditemukan :(');
        }

        if (Cache::has('latest_news')) {
            $newsSet = Cache::get('latest_news');
        } else {
            $newsSet = News::with('category')->orderBy('id', 'desc')->take(50)->get();
            Cache::put('latest_news', $newsSet, Carbon::now()->addMinutes(15));
        }

        $otherNews = $newsSet->random(3);

        return view('news', compact(['news', 'otherNews']));
    }
}
