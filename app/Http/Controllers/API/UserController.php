<?php

namespace Noox\Http\Controllers\API;

use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;
use JWTAuth;
use Noox\Models\User;

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
}
