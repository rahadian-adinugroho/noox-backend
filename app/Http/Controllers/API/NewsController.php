<?php

namespace Noox\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
use JWTAuth;
use Noox\Models\News;

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
        $news = News::with([
            'comments' => function($query)
            {
                $query
                ->select('id', 'user_id', 'news_id', 'created_at', 'content')
                ->with(['latestReplies' => function($query)
                {
                    $query->select('id', 'user_id', 'news_id', 'created_at', 'content', 'parent_id')->withCount('likes');
                },
                'latestReplies.author' => function ($query) 
                {
                    $query->select('id', 'name');
                }])
                ->withCount(['replies', 'likes'])
                ->whereNull('parent_id')
                ->paginate(10);
            },
            'comments.author' => function ($query) 
            {
                $query->select('id', 'name');
            },
            'source', 'category'])->withCount(['likes', 'comments'])->find($id);

        if(!is_null($news))
        {
            try {
                if ($user = JWTAuth::parseToken()->authenticate()) {
                    if ($news->readers()->where('user_id', $user->id)->exists()) {
                        $news->readers()->updateExistingPivot($user->id, ['last_read' => Carbon::now()]);
                    } else {
                        $news->readers()->attach($user->id);
                    }
                }
            } catch(\Tymon\JWTAuth\Exceptions\JWTException $e) {
                // token not supplied by the requester
            }
            return response()->json(compact('news'));
        }
        else
        {
            return $this->response->errorNotFound('News not found.');
        }
    }
}
