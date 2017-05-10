<?php

namespace Noox\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
use JWTAuth;
use Noox\Models\News;
use Noox\Models\NewsComment;

/**
 * @resource News
 *
 * The list of news and its contents.
 */
class NewsController extends BaseController
{
    /**
     * News details.
     * Return the details of the news along with some comments
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
                        }])->exists();
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
     * Get news comments.
     * Retrieve comments for the news. You may set 'perPage' query in the URL to set item per page. Refer to 'next_page_url' in the response to retrieve next page url.
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
            $data =  
            $news->comments()
            ->select('id', 'news_id', 'user_id', 'created_at', 'content')
            ->with([
                'latestReplies' => function($q) {
                    $q
                    ->select('id', 'news_id', 'user_id', 'created_at', 'content', 'parent_id')
                    ->with(['author' => function($q) {
                        $q->select('id', 'name');
                    }])
                    ->withCount('likes');
                },'author' => function($q) {
                    $q->select('id', 'name');
                }])
            ->withCount(['replies', 'likes'])
            ->paginate($r->input('perPage'));
            return response()->json(compact('data'));
        } else {
            return $this->response->errorNotFound('News not found.');
        }
    }

    /**
     * Get comment details.
     * Will return the comment and its replies. The replies are paginated. You may set 'perPage' query in the URL to set reply per page. Refer to 'next_page_url' in the response to retrieve next page url.
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
                    $q->where('user_id', $userId);
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
                            $q->where('user_id', $userId);
                        }]);
                }
                $replies = $query->paginate($r->input('perPage'), ['*'], 'replyPage');
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
        $user = JWTAuth::toUser();
        $comment = new NewsComment;

        $comment->news_id = $newsId;
        $comment->content = $request->input('content');

        try {
            $res = $user->comments()->save($comment);
            return $this->response->created(url('/api/news_comment/'.$res->id), ['status' => true, 'message' => 'Comment saved.']);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->response->errorBadRequest('News not found.');
        }
        return $this->response->errorInternal('Unable to save comment at this moment.');
    }
}
