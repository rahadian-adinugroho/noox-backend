<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use Noox\Models\News;
use Noox\Models\NewsComment;
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

    /**
     * Return the list of reported comments.
     * 
     * @return Illuminate\Http\Response
     */
    public function reportedComments()
    {
        // in case of table name change, we use table name from model
        $tableName = (new NewsComment)->getTable();
        $comments = NewsComment::select([
        \DB::raw('`'. $tableName .'`' . '.`id`'),
        'news_id',
        'user_id',
        \DB::raw('LEFT(`content`, 100) as `content`'),
        \DB::raw('`'. $tableName .'`' . '.`created_at`')])
        ->has('reports')
        ->with(['author' => function($q){
            $q->select(['id', 'name']);
        }, 'news' => function($q){
            $q->select(['id', 'title']);
        }])
        ->withCount('reports');

        return Datatables::of($comments)->addColumn('action', function ($comment) {
                return '<a href="'.route('cms.news.comment.details', [$comment->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>'
                        .'<a href="'.route('cms.news.comment.reports', [$comment->id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> View Reports</a>';
            })
            ->make(true);
    }
}

