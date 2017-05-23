<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use Noox\Models\News;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Noox\Http\Middleware\JWTMultiAuth::class);
    }

    /**
     * Return the list of news.
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::with('source')->select(['id', 'title', 'pubtime', 'author', 'source_id']);

        return Datatables::of($news)->addColumn('action', function ($news) {
                return '<a href="'.route('cms.news.details', [$news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Return the list of reported news.
     * 
     * @return Illuminate\Http\Response
     */
    public function reported()
    {
        $news = News::select(['id', 'title', 'pubtime', 'source_id'])
            ->with('source')
            ->withCount('reports')
            ->has('reports');

        return Datatables::of($news)->addColumn('action', function ($news) {
                return '<a href="'.route('cms.news.details', [$news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>'
                        .'<a href="'.route('cms.news.reports', [$news->id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> View Reports</a>';
            })
            ->make(true);
    }

    /**
     * Return deleted news.
     * 
     * @return Illuminate\Http\Response
     */
    public function deleted()
    {
        $news = News::select(['id', 'title', 'deleted_at', 'pubtime', 'author'])->with('source')->onlyTrashed();

        return Datatables::of($news)->addColumn('action', function ($news) {
                return '<a href="'.route('cms.news.details', [$news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }
}

