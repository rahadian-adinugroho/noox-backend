<?php

namespace Noox\Http\Controllers\API;

use Illuminate\Http\Request;
use JWTAuth;
use Noox\Models\User;

/**
 * @resource User
 *
 * The list of users and its details.
 */
class UserController extends BaseController
{
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
        $id = User::create([
           'email'    => $request->input('email'),
           'password' => bcrypt($request->input('password')),
           'name'     => $request->input('name'),
           'gender'   => $request->input('gender', null),
           'birthday' => $request->input('birthdat', null),
           ])->id;

        if ($id) {
            return $this->response->created(url('/api/user/'.$id), ['status' => true, 'message' => 'User created.']);
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
                $query->select('user_id', 'news_id', 'created_at', 'content')->whereNull('parent_id');
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
}
