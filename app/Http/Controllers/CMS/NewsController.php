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
     * Return all news
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('cms.news');
    }

    /**
     * Return all reported news
     * 
     * @return Illuminate\Http\Response
     */
    public function reported()
    {
        return view('cms.news_reported');
    }

    /**
     * Return all deleted news
     * 
     * @return Illuminate\Http\Response
     */
    public function deleted()
    {
        return view('cms.news_deleted');
    }

    /**
     * Return all reported comments
     * 
     * @return Illuminate\Http\Response
     */
    public function reportedComments()
    {
        return view('cms.comments_reported');
    }

    /**
     * View the details of the requested admin.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function view($id)
    {
        if (! $data = News::with(['source', 'category'])->withCount(['reports', 'comments'])->find($id)) {
            abort(404);
        }

        return view('cms.news_details', compact('data'));
    }
}
