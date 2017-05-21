<?php

namespace Noox\Http\Controllers\API;

use Noox\Models\User;
use Noox\Models\NewsCategory;
use Noox\Http\Controllers\Traits\FacebookAuthentication;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @resource User
 *
 * The list of users and its details.
 */
class UserController extends BaseController
{
    use FacebookAuthentication;

    /**
     * Register a user.
     *
     * @param string $email
     * @param string $password At least 6 in length
     * @param char $gender Either f or m.
     * @param date $birthday [OPT] In Y-m-d format.
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(\Noox\Http\Requests\UserRegistrationRequest $request)
    {
        $fbid = '';
        if ($request->input('fb_token')) {
            $fbid = $this->extractFbId($request);
            if ($fbid && User::where('fb_id', '=', $fbid)->count()) {
                return $this->response
                ->errorBadRequest('This Facebook account has already linked with '.config('app.name').' app.');
            }
        }

        $user = User::create([
            'fb_id'    => $fbid ?: null,
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name'     => $request->input('name'),
            'gender'   => $request->input('gender', null),
            'birthday' => $request->input('birthdat', null),
            ]);

        if ($user) {
            $token = JWTAuth::fromUser($user, ['type' => 'user']);
            $tokenPack = [
            'valid_until'   => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp,
            'refresh_before' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->timestamp,
            'token'        => $token,
            ];

            return $this->response->created(
                url('/api/user/'.$user->id),
                ['status' => true, 'message' => 'User created.', 'token' => $tokenPack]);
        } else {
            return $this->response->errorBadRequest();
        }
    }

    /**
     * Get user details.
     * Get data for another user profile page in noox app.
     * 
     * @param  integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        $data = User::with([
            'comments' => function($query){
                $query->select('user_id', 'news_id', 'created_at', 'content')->whereNull('parent_id')->orderBy('created_at', 'desc');
            },
            'comments.news' => function($query){
                $query->select('id', 'title');
            }
            ])->select('id', 'name', 'created_at as member_since', 'level', 'xp')->withCount([
            'comments' => function($query){
                $query->whereNull('parent_id');
            },
            'newsLikes'
            ])->find($id);

            if ($data) {
                return response()->json(compact('data'));
            } else {
                return $this->response->errorNotFound('User not found.');
            }
        }

    /**
     * Submit new user preferences.
     * In JSON: {"categories" : ["national", "crime"]}
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function updatePreferences(Request $request)
    {
        if (! $request->has('categories')) {
            $this->response->errorBadRequest('Categories not supplied.');
        }
        $user = User::find(JWTAuth::getPayload()->get('sub'));

        $categories = $request->input('categories');
        $catIds = $this->getCategoriesId($categories);

        if ($user->newsPreferences()->sync($catIds)) {
            return response()->json(['message' => 'Preferences saved.']);
        }
    }

    /**
     * Get current user preferences.
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewPreferences()
    {
        $user = User::find(JWTAuth::getPayload()->get('sub'));

        $categories = $user->newsPreferences()->get()->map(function($category){
            return $category->name;
        });

        return response()->json(compact('categories'));
    }

    /**
     * Submit a report for user.
     * Use this API to submit a report for news with is {id}. The submitter has to be authenticated before doing this.
     * 
     * @param  string $content
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitReport(\Noox\Http\Requests\SubmitReportRequest $request, $userId)
    {
        if (! $user = User::find($userId)) {
            return $this->response->error('User not found.', 422);
        }

        $report            = new \Noox\Models\Report;
        $report->user_id   = JWTAuth::getPayload()->get('sub');
        $report->content   = $request->input('content');
        $report->status_id = \Noox\Models\ReportStatus::where('name', '=', 'open')->firstOrFail()->id;

        if ($res = $user->reports()->save($report)) {
            return $this->response->created(null, ['status' => true, 'message' => 'Report submitted.']);
        }
        return $this->response->errorInternal('Unable to save your report at this moment.');
    }

    /**
     * Convert the categories into its own id.
     * 
     * @param  array $categories
     * @return array
     */
    protected function getCategoriesId(array $categories)
    {
        return NewsCategory::select('id')
        ->whereIn('name', $categories)
        ->get()
        ->map(function($data){
            return $data->id;
        });
    }
}
