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
                return '<a href="'.route('cms.news.details', [$news->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
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
        $news = News::select(['id', 'source_id', 'title', 'deleted_at', 'pubtime', 'author'])->with('source')->onlyTrashed();

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
                return '<a href="'.route('cms.news.comment.details', [$comment->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Return the list of news comments.
     *
     * @param  int $id
     * @return Illuminate\Http\Response
     */
    public function newsComments($id)
    {
        if (! $news = News::withTrashed()->find($id)) {
            return response(['message' => 'News not found.'], 422);
        }

        // in case of table name change, we use table name from model
        $tableName = (new NewsComment)->getTable();
        $comments = $news->comments()->select([
        \DB::raw('`'. $tableName .'`' . '.`id`'),
        'news_id',
        'user_id',
        \DB::raw('LEFT(`content`, 100) as `content`'),
        \DB::raw('`'. $tableName .'`' . '.`created_at`')])
        ->whereNull('parent_id')
        ->with(['author' => function($q){
            $q->select(['id', 'name']);
        }, 'news' => function($q){
            $q->select(['id', 'title']);
        }])
        ->withCount(['reports', 'replies']);

        return Datatables::of($comments)->addColumn('action', function ($comment) {
                $actionHtml = '<a href="'.route('cms.news.comment.details', [$comment->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
                if ($comment->reports_count > 0) {
                    $actionHtml . '<a href="'.route('cms.news.comment.reports', [$comment->id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> View Reports</a>';
                }
                return $actionHtml;
            })
            ->make(true);
    }

    /**
     * Restore the list of news reports.
     * 
     * @param  int $id
     * @return Illuminate\Http\Response
     */
    public function newsReports($id)
    {
        if (! $news = News::withTrashed()->find($id)) {
            return response(['message' => 'News not found.'], 422);
        }

        $reports = $news->reports()->select(['id', 'reporter_id', \DB::raw('LEFT(`content`, 100) as `content`'), 'status_id', 'reportable_type', 'created_at'])
        ->with(['reporter' => function($q){
            $q->select(['id', 'name']);
        }, 'status']);

        return Datatables::of($reports)->addColumn('action', function ($report) {
                return '<a href="'.route('cms.report.details', [$report->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Update the news.
     * 
     * @param  Illuminate\Http\Request
     * @param  int
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! $news = News::withTrashed()->find($id)) {
            return response(['message' => 'News not found.'], 422);
        }

        $validation = [
            'title' => 'required|min:10',
            'content' => 'required|min:400'
        ];
        $this->validate($request, $validation);

        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->save();

        return response(['message' => 'News successfully updated.']);
    }

    /**
     * Delete the news.
     * 
     * @param  int
     * @return Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (! $news = News::withTrashed()->find($id)) {
            return response(['message' => 'News not found.'], 422);
        }
        if ($news->deleted_at) {
            return response(['message' => 'News already deleted.'], 422);
        }
        
        $news->delete();

        return response(['message' => 'News successfully deleted.']);
    }

    /**
     * Restore the news.
     * 
     * @param  int
     * @return Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! $news = News::withTrashed()->find($id)) {
            return response(['message' => 'News not found.'], 422);
        }
        if (is_null($news->deleted_at)) {
            return response(['message' => 'News is not deleted.'], 422);
        }
        
        $news->deleted_at = null;
        $news->save();

        return response(['message' => 'News successfully restored.']);
    }

    /**
     * Return the list of comment replies.
     *
     * @param  int $id
     * @return Illuminate\Http\Response
     */
    public function commentReplies($id)
    {
        if (! $comment = NewsComment::find($id)) {
            return response(['message' => 'Comment not found.'], 422);
        }

        // in case of table name change, we use table name from model
        $tableName = (new NewsComment)->getTable();
        $replies = $comment->replies()->select([
        \DB::raw('`'. $tableName .'`' . '.`id`'),
        'news_id',
        'user_id',
        \DB::raw('LEFT(`content`, 100) as `content`'),
        \DB::raw('`'. $tableName .'`' . '.`created_at`')])
        ->with(['author' => function($q){
            $q->select(['id', 'name']);
        }, 'news' => function($q){
            $q->select(['id', 'title']);
        }])
        ->withCount(['reports', 'replies']);

        return Datatables::of($replies)->addColumn('action', function ($comment) {
                $actionHtml = '<a href="'.route('cms.news.comment.details', [$comment->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
                if ($comment->reports_count > 0) {
                    $actionHtml . '<a href="'.route('cms.news.comment.reports', [$comment->id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> View Reports</a>';
                }
                return $actionHtml;
            })
            ->make(true);
    }

    /**
     * Restore the list of comment reports.
     * 
     * @param  int $id
     * @return Illuminate\Http\Response
     */
    public function commentReports($id)
    {
        if (! $comment = NewsComment::find($id)) {
            return response(['message' => 'Comment not found.'], 422);
        }

        $reports = $comment->reports()->select(['id', 'reporter_id', \DB::raw('LEFT(`content`, 100) as `content`'), 'status_id', 'reportable_type', 'created_at'])
        ->with(['reporter' => function($q){
            $q->select(['id', 'name']);
        }, 'status']);

        return Datatables::of($reports)->addColumn('action', function ($report) {
                return '<a href="'.route('cms.report.details', [$report->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }
}

