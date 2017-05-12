<?php

namespace Noox\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Noox\Models\News;

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

        $otherNews = News::with('category')->inRandomOrder()->take(3)->get();

        return view('news', compact(['news', 'otherNews']));
    }
}
