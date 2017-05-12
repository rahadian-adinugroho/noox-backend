<?php

namespace Noox\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Noox\Models\News;

class NewsController extends Controller
{
    public function read($newsId)
    {
        if (! $news = News::with(['category', 'source'])->find($newsId)) {
            abort(404, 'Artikel tidak ditemukan :(');
        }

        $otherNews = News::with('category')->inRandomOrder()->take(3)->get();

        return view('news', compact(['news', 'otherNews']));
    }
}
