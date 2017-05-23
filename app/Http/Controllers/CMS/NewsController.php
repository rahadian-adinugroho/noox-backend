<?php

namespace Noox\Http\Controllers\CMS;

use Noox\Models\News;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Return the all news
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('cms.news');
    }

    /**
     * Return the all reported news
     * 
     * @return Illuminate\Http\Response
     */
    public function reported()
    {
        return view('cms.news_reported');
    }

    /**
     * Return the all deleted news
     * 
     * @return Illuminate\Http\Response
     */
    public function deleted()
    {
        return view('cms.news_deleted');
    }

    /**
     * View the details of the requested admin.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function view($id)
    {
        if (! $news = News::find($id)) {
            abort(404);
        }

        return $news;
    }
}
