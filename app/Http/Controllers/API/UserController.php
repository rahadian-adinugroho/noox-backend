<?php

namespace Noox\Http\Controllers\API;

use JWTAuth;
use Auth;
use Noox\Models\User;
use Noox\Models\NewsCategory;
use Noox\Models\Setting;
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

            $user->addFcmToken($request->input('fcm_token'));

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

        $user->birthday = $request->input('birthday');
        $user->name     = $request->input('name');
        $user->gender   = $request->input('gender');

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
            ])->select('id', 'fb_id', 'name', 'created_at as member_since')->withCount([
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
            ])->select('id', 'fb_id', 'name', 'gender', 'birthday', 'created_at as member_since', 'experience')->withCount([
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
        ->orderBy('earn_date', 'desc')
        ->get();

        return response()->json(compact('data'));
    }

    /**
     * Add user's achievement.
     * Will return forbidden if the user is not eligible for the achievement. You can get user stats in /api/personal/stats
     *
     * Param: {key: "RN-national-1"}
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addAchievement(\Noox\Http\Requests\AddAchievementRequest $request)
    {
        $user = $this->auth->user();

        # check if achievement exist
        if (! $achievement = \Noox\Models\Achievement::where('key', $request->input('key'))->first()) {
            $this->response->error('Achievement does not exist.', 422);
        }
        # check if the user is eligible
        if ($this->checkAchievementEligibility($achievement, $user)) {
            try {
                $user->achievements()->attach($achievement, ['earn_date' => Carbon::now()]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    return $this->response->error('This user already has this achievement.', 422);
                } else {
                    return $this->response->errorInternal('Please try again later.');
                }
            }
        } else {
            return $this->response->errorForbidden('This user is not eligible for this achievement.');
        }
        # return the result
        return response()->json(['message' => 'Achievement added.']);
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
        ->select(['key', 'value'])
        ->get();

        return response()->json(compact('data'));
    }

    /**
     * Update user's settings.
     * Format:
     * {
     *    "settings" : {"top_news_notif": 0, "comment_liked_notif": 1, "comment_replied_notif": 0, "report_approved_notif": 1}
     * }
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request)
    {
        if (! $request->has('settings')) {
            $this->response->errorBadRequest('Settings is not supplied.');
        }
        $user = $this->auth->user();

        $settings = $request->input('settings');

        if ($user->settings()->sync($this->formatNewSettings($settings))) {
            return response()->json(['message' => 'Settings saved.']);
        }
    }

    /**
     * Add FCM token.
     * Add an FCM token to the current user. The token either added or updated to the database depending on conditions.
     * 
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function addFcmToken(\Noox\Http\Requests\AddFcmTokenRequest $request)
    {
        $user = $this->auth->user();

        $user->addFcmToken($request->input('fcm_token'));

        return response()->json(['message' => 'FCM token saved.']);
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
     * Format the supplied settings to its ids with values.
     * 
     * @param  array  $newSettings
     * @return array
     */
    protected function formatNewSettings(array $newSettings)
    {
        // get all available settings
        $settings = Setting::get();

        // match the submitted settings with id from database & update with submitted value.
        $formattedSettings = [];
        foreach ($settings as $key => $setting) {
            if (isset($newSettings[$setting->key])) {
                // if the setting is supplied, update with the new value.
                $formattedSettings[$setting->id]['value'] = $newSettings[$setting->key];
            } else {
                // else update with the default value.
                $formattedSettings[$setting->id]['value'] = $setting->default_value;
            }
        }

        return $formattedSettings;
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

    /**
     * Check whether the supplied user is eligible for the achievement.
     * 
     * @param  \Noox\Models\Achievement $achievement
     * @param  \Noox\Models\User        $user
     * @return bool
     */
    protected function checkAchievementEligibility(\Noox\Models\Achievement $achievement, User $user)
    {
        $parsedAcv = null;
        $userStats = $user->getStats();
        preg_match('/(?:(?P<main_category>[A-Z]+)-(?P<sub_category>[\w]+)|(?P<category>[A-Z]+))-(?P<tier>\d)/', $achievement->key, $parsedAcv);

        if (! $parsedAcv['tier'] == '') {
            switch ($parsedAcv['tier']) {
                case '1':
                    $minCount = 5;
                    break;

                case '2':
                    $minCount = 20;
                    break;

                case '3':
                    $minCount = 45;
                    break;

                case '4':
                    $minCount = 80;
                    break;

                case '5':
                    $minCount = 125;
                    break;

                default:
                    return false;
            }

            if ($parsedAcv['main_category'] == 'RN') {
                return $userStats['news_read_count'][$parsedAcv['sub_category']] >= $minCount;
            } elseif ($parsedAcv['category'] == 'SR') {
                return $userStats['news_report_count'] >= $minCount;
            } elseif ($parsedAcv['category'] == 'AR') {
                return $userStats['approved_news_report_count'] >= $minCount;
            }
        }
    }
}
