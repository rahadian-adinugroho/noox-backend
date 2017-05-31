<?php

namespace Noox\Http\Controllers\API;

use JWTAuth;
use Auth;
use Noox\Models\User;
use Noox\Models\NewsCategory;
use \Noox\Models\Setting;
use Noox\Http\Controllers\Traits\FacebookAuthentication;
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
            'password' => bcrypt($request->input('password'), ['rounds' => 12]),
            'name'     => $request->input('name'),
            'gender'   => $request->input('gender', null),
            'birthday' => $request->input('birthday', null),
            ]);

        if ($user) {
            $token = JWTAuth::fromUser($user, ['type' => 'user']);
            $tokenPack = [
            'valid_until'   => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp,
            'refresh_before' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->timestamp,
            'token'        => $token,
            ];

            $user->settings()->attach($this->getInitialSettings());
            return $this->response->created(
                url('/api/user/'.$user->id),
                ['status' => true, 'message' => 'User created.', 'token' => $tokenPack]);
        } else {
            return $this->response->errorBadRequest();
        }
    }

    /**
     * Update user's profile.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(\Noox\Http\Requests\UpdateProfileRequest $request)
    {
        $user = $this->auth->user();

        $user->email  = $request->input('email');
        $user->name   = $request->input('name');
        $user->gender = $request->input('gender');

        if ($user->save()) {
            return response('');
        } else {
            return $this->response->errorInternal();
        }
    }

    /**
     * Update user's password.
     * Please put this in the header: {"Content-Type":"application/x-www-form-urlencoded"}, if the default is different from that.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(\Noox\Http\Requests\UpdatePasswordRequest $request)
    {
        $user     = $this->auth->user();

        $email       = $user->email;
        $oldPassword = $request->input('oldpassword');

        if (! Auth::attempt(['email' => $email, 'password' => $oldPassword])) {
            $this->response->errorBadRequest('Old password is invalid.');
        }

        $user->password = bcrypt($request->input('newpassword'), ['rounds' => 12]);

        if ($user->save()) {
            return response('');
        } else {
            return $this->response->errorInternal();
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
            'latestAchievement' => function($query){
                $query->select(['title'])->first();
            }
            ])->select('id', 'fb_id', 'name', 'created_at as member_since', 'level')->withCount([
            'comments' => function($query){
                $query->whereNull('parent_id');
            },
            'likedNews'
            ])->find($id);

            if ($data) {
                return response()->json(compact('data'));
            } else {
                return $this->response->errorNotFound('User not found.');
            }
    }

    /**
     * Get user comments.
     * 
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function comments($id)
    {
        if (! $user = User::find($id)) {
            $this->response->errorNotFound('User not found.');
        }

        $data = $user->comments()
        ->select(['id', 'news_id', 'created_at', 'content'])
        ->with(['news' => function($q){
            $q->select('id', 'cat_id', 'title');
        }, 'news.category'])
        ->whereNull('parent_id')
        ->latest()
        ->paginate(10);

        return response()->json(compact('data'));
    }

    /**
     * Get user's personal data.
     * 
     * @return \Illuminate\Http\Response
     */
    public function personalDetails()
    {
        $data = User::with([
            'latestAchievement' => function($query){
                $query->select(['title'])->first();
            },
            ])->select('id', 'name', 'gender', 'created_at as member_since', 'level', 'experience')->withCount([
            'comments' => function($query){
                $query->whereNull('parent_id');
            },
            'likedNews',
            'achievements',
            ])->find($this->auth->user()->id);

        return response()->json(compact('data'));
    }

    /**
     * Get user's news comments.
     * 
     * @return \Illuminate\Http\Response
     */
    public function personalComments()
    {
        $user = $this->auth->user();

        $data = $user->comments()
        ->select(['id', 'news_id', 'created_at', 'content'])
        ->with(['news' => function($q){
            $q->select('id', 'cat_id', 'title');
        }, 'news.category'])
        ->whereNull('parent_id')
        ->latest()
        ->paginate(10);

        return response()->json(compact('data'));
    }

    /**
     * Get user's liked news.
     * 
     * @return \Illuminate\Http\Response
     */
    public function personalLikedNews()
    {
        $user = $this->auth->user();

        $data = $user->likedNews()
        ->select('news_id', 'source_id', 'title', 'pubtime')
        ->with(['source'])
        ->orderBy('pivot_liked_at', 'desc')
        ->paginate(10);

        return $this->response->paginator($data, new \Noox\Transformers\LikedNewsTransformer);
    }

    /**
     * Get user's personal stats.
     * 
     * @return \Illuminate\Http\Response
     */
    public function personalStats()
    {
        $user = $this->auth->user();

        $data = $user->getStats();

        return response()->json(compact('data'));
    }

    /**
     * Get user's personal achievements.
     * 
     * @return \Illuminate\Http\Response
     */
    public function personalAchievements()
    {
        $user = $this->auth->user();

        $data = $user->achievements()
        ->select(['id', 'key', 'title', 'description', 'earn_date'])
        ->orderBy('pivot_earn_date', 'desc')
        ->get();

        return response()->json(compact('data'));
    }

    /**
     * Get user's settings.
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewSettings()
    {
        $user = $this->auth->user();

        $data = $user->settings()
        ->select(['id', 'key', 'value'])
        ->get();

        return response()->json(compact('data'));
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
        $user = $this->auth->user();

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
        $user = $this->auth->user();

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

        $report              = new \Noox\Models\Report;
        $report->reporter_id = JWTAuth::getPayload()->get('sub');
        $report->content     = $request->input('content');
        $report->status_id   = \Noox\Models\ReportStatus::where('name', '=', 'open')->firstOrFail()->id;

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

    /**
     * Get initial settings id and its default value to attach it to the user.
     * 
     * @return array
     */
    protected function getInitialSettings()
    {
        $settings = Setting::get();

        $initSettings = [];
        foreach ($settings as $key => $setting) {
            $initSettings[$setting->id]['value'] =  $setting->default_value;
        }

        return $initSettings;
    }
}
