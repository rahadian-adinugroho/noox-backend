<?php

namespace Noox\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
use JWTAuth;
use Notification;
use Noox\Models\Admin;
use Noox\Models\News;
use Noox\Models\NewsComment;
use Noox\Events\CommentRepliedEvent;
use Noox\Notifications\NewsCommentReplied;
use Noox\Notifications\NewsCommentLiked;
use Noox\Notifications\NewsReportBeyondThreshold;
use Noox\Events\CommentLikedEvent;
use Noox\Events\NewsReportedEvent;

/**
 * @resource News
 *
 * The list of news and its contents.
 */
class NewsController extends BaseController
{
    /**
     * News details.
     * Return the details of the news with 'likes' status if the requester supplied a valid token.
     * 
     * @param  integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        // try to get the user id if token exist
        try {
            $userId = JWTAuth::parseToken()->getPayload()->get('sub');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $userId = null;
        }
        $query = News::
        with(['source', 'category'])
        ->withCount(['likes', 'comments']);

        if ($userId) {
            $query->with(['likes' => function($q) use ($userId) 
            {
                $q->select('user_id', 'name')->where('user_id', $userId);
            }]);
        }
        $data = $query->find($id);

        if(!is_null($data))
        {
            if ($userId) {
                if ($data->readers()->where('user_id', $userId)->exists()) {
                    $data->readers()->updateExistingPivot($userId, ['last_read' => Carbon::now()]);
                } else {
                    $data->readers()->attach($userId);
                }
            }
            return response()->json(compact('data'));
        }
        else
        {
            return $this->response->errorNotFound('News not found.');
        }
    }

    /**
     * News details.
     * Return the details of the news along with some comments.
     * 
     * @param  integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function detailsWithComments($id)
    {
        // try to get the user id if token exist
        try {
            $userId = JWTAuth::parseToken()->getPayload()->get('sub');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $userId = null;
        }
        $query = News::
        with([
            'comments' => function($query) use ($userId)
            {
                $query
                ->select('id', 'user_id', 'news_id', 'created_at', 'content')
                ->with(['latestReplies' => function($query) use ($userId)
                {
                    $query
                    ->select('id', 'user_id', 'news_id', 'created_at', 'content', 'parent_id')
                    ->with(['author' => function ($query) 
                    {
                        $query->select('id', 'name');
                    }])
                    ->withCount('likes');

                    if ($userId) {
                        $query->with(['likes' => function($q) use ($userId) 
                        {
                            $q->where('user_id', $userId);
                        }]);
                    }

                },])
                ->withCount(['replies', 'likes'])
                ->whereNull('parent_id');

                if ($userId) {
                    $query->with(['likes' => function($q) use ($userId) 
                    {
                        $q->where('user_id', $userId);
                    }]);
                }

                $query->take(10);
            },
            'comments.author' => function ($query) 
            {
                $query->select('id', 'name');
            },
            'source', 'category'])
        ->withCount(['likes', 'comments']);

        if ($userId) {
            $query->with(['likes' => function($q) use ($userId) 
            {
                $q->where('user_id', $userId);
            }]);
        }

        $data = $query->find($id);

        if(!is_null($data))
        {
            if ($userId) {
                if ($data->readers()->where('user_id', $userId)->exists()) {
                    $data->readers()->updateExistingPivot($userId, ['last_read' => Carbon::now()]);
                } else {
                    $data->readers()->attach($userId);
                }
            }
            return response()->json(compact('data'));
        }
        else
        {
            return $this->response->errorNotFound('News not found.');
        }
    }

    /**
     * Submit a like for news.
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitLike($newsId)
    {
        if (! $news = News::find($newsId)) {
            $this->response->errorNotFound('News does not exist.');
        }

        $user = auth()->getUser();

        try {
            $news->likes()->attach($user->id);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->response->errorBadRequest('This user already liked this news.');
            } else {
                $this->response->errorInternal('Please try again later.');
            }
        }
        
        return response()->json(['message' => 'User like has been saved.']);
    }

    /**
     * Unlike a news.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteLike($newsId)
    {
        if (! $news = News::find($newsId)) {
            $this->response->errorNotFound('News does not exist.');
        }

        $user = auth()->getUser();

        try {
            $news->likes()->detach($user->id);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->response->errorInternal('Please try again later.');
        }

        return $this->response->noContent();
    }

    /**
     * Get news comments.
     * Retrieve comments for the news. Refer to 'next_page_url' in the response to retrieve next page url.
     * 
     * @param  integer $perPage
     * @param  integer $page
     * 
     * @return \Illuminate\Http\Response
     */
    public function getComments(Request $r, $newsId)
    {
        // try to get the user id if token exist
        try {
            $userId = JWTAuth::parseToken()->getPayload()->get('sub');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $userId = null;
        }
        $news = News::find($newsId);
        if ($news) {
            $query =  
            $news->comments()
            ->select('id', 'news_id', 'user_id', 'created_at', 'content')
            ->with([
                'latestReplies' => function($q) use ($userId) {
                    $q
                    ->select('id', 'news_id', 'user_id', 'created_at', 'content', 'parent_id')
                    ->with(['author' => function($q) {
                        $q->select('id', 'name');
                    }])
                    ->withCount('likes');

                    if ($userId) {
                        $q->with(['likes' => function($q) use ($userId) 
                        {
                            $q->select('user_id', 'name')->where('user_id', $userId);
                        }]);
                    }
                },'author' => function($q) {
                    $q->select('id', 'name');
                }])
            ->withCount(['replies', 'likes']);

            if ($userId) {
                $query->with(['likes' => function($q) use ($userId) 
                {
                    $q->where('user_id', $userId);
                }]);
            }

            $data = $query->paginate();
            return response()->json(compact('data'));
        } else {
            return $this->response->errorNotFound('News not found.');
        }
    }

    /**
     * Get comment details.
     * Will return the comment and its replies. The replies are paginated. Refer to 'next_page_url' in the response to retrieve next page url.
     * The 'likes' variable will exist if the client supplied a valid JWT token. The 'likes' variable determines whether the user already liked the comment or not. If it is not an empty array, it means the user already liked the comment with that particular 'likes' attribute.
     * 
     * @param  integer $perPage
     * @param  integer $page
     * 
     * @return [type]          [description]
     */
    public function commentDetails(Request $r, $newsId)
    {
        try {
            $userId = JWTAuth::parseToken()->getPayload()->get('sub');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $userId = null;
        }

        $q = NewsComment::select('id', 'user_id', 'created_at', 'content', 'parent_id')
        ->with([
            'author' => function($q) {
                $q->select('id', 'name');
            },])
        ->withCount('likes');
        if ($userId) {
            $q->with([
                'likes' => function($q) use ($userId) {
                    $q->select('user_id', 'name')->where('user_id', $userId);
                }]);
        }
        $comment = $q->find($newsId);

        if ($comment) {
            if (is_null($comment->parent_id)) {
                $query = $comment->replies()
                ->select('id', 'user_id', 'created_at', 'content', 'parent_id')
                ->with(['author' => function($q) {
                    $q->select('id', 'name');
                }])
                ->withCount('likes');

                if ($userId) {
                    $query->with([
                        'likes' => function($q) use ($userId) {
                            $q->select('user_id', 'name')->where('user_id', $userId);
                        }]);
                }

                $replies = $query->paginate();
            } else {
                $replies = null;
            }
            return response()->json(compact('comment', 'replies'));
        } else {
            return $this->response->errorNotFound('Comment not found.');
        }
    }

    /**
     * Submit comment.
     * Submit a comment for a particular news.
     * 
     * @param  string $content
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitComment(\Noox\Http\Requests\SubmitNewsCommentRequest $request, $newsId)
    {
        if (! $news = News::find($newsId)) {
            return $this->response->error('News not found.', 422);
        }

        $comment          = new NewsComment;
        $comment->user_id = auth()->getUser()->id;
        $comment->content = $request->input('content');

        if ($res = $news->comments()->save($comment)) {
            return $this->response->created(url('/api/news_comment/'.$res->id), ['status' => true, 'message' => 'Comment saved.']);
        }
        return $this->response->errorInternal('Unable to save comment at this moment.');
    }

    /**
     * Submit reply to a comment.
     * Use this API to submit a reply to comment with id {id}.
     * 
     * @param string $content   
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitCommentReply(\Noox\Http\Requests\SubmitNewsCommentRequest $request, $commentId)
    {
        if (!$parent = NewsComment::find($commentId)) {
            return $this->response->error('Comment not found.', 422);
        }
        if ($parent->parent_id) {
            return $this->response->error('You cannot reply a comment reply.', 422);
        }

        $comment          = new NewsComment;
        $comment->user_id = auth()->getUser()->id;
        $comment->news_id = $parent->news_id;
        $comment->content = $request->input('content');

        if ($res = $parent->replies()->save($comment)) {
            $parentAuthor = $parent->author;
            if (auth()->getUser()->id != $parentAuthor->id) {
                $res->author = auth()->getUser();
                $parentAuthor->notify(new NewsCommentReplied($parent, $res));
            }
            return $this->response->created(url('/api/news_comment/'.$res->id), ['status' => true, 'message' => 'Comment saved.']);
        }
        return $this->response->errorInternal('Unable to save reply at this moment.');
    }

    /**
     * Submit a like for comment.
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitCommentLike($commentId)
    {
        if (!$comment = NewsComment::find($commentId)) {
            return $this->response->error('Comment not found.', 422);
        }

        $user = auth()->getUser();

        try {
            $comment->likes()->attach($user->id);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->response->errorBadRequest('This user already liked this comment.');
            } else {
                $this->response->errorInternal('Please try again later.');
            }
        }
        $commentAuthor = $comment->author;
        if ($user->id != $commentAuthor->id) {
            $commentAuthor->notify(new NewsCommentLiked($comment, $user));
        }
        return response()->json(['message' => 'User like has been saved.']);
    }

    /**
     * Unlike a comment.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteCommentLike($commentId)
    {
        if (!$comment = NewsComment::find($commentId)) {
            return $this->response->error('Comment not found.', 422);
        }

        $user = auth()->getUser();

        try {
            $comment->likes()->detach($user->id);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->response->errorInternal('Please try again later.');
        }
        
        return $this->response->noContent();
    }

    /**
     * Submit a report for news.
     * Use this API to submit a report for news with is {id}. The submitter has to be authenticated before doing this.
     * 
     * @param  string $content
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitReport(\Noox\Http\Requests\SubmitReportRequest $request, $newsId)
    {
        if(! $news = News::find($newsId)) {
            return $this->response->error('News not found.', 422);
        }

        $reporter = auth()->getUser();

        $report              = new \Noox\Models\Report;
        $report->reporter_id = $reporter->id;
        $report->content     = $request->input('content');
        $report->status_id   = \Noox\Models\ReportStatus::where('name', '=', 'open')->firstOrFail()->id;

        if ($res = $news->reports()->save($report)) {
            if ($news->reports()->count() > config('noox.news_report_threshold')) {
                Notification::send(Admin::all(), new NewsReportBeyondThreshold($news));
            }
            return $this->response->created(null, ['status' => true, 'message' => 'Report submitted.']);
        }
        return $this->response->errorInternal('Unable to save your report at this moment.');
    }
}
