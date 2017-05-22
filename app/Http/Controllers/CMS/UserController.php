<?php

namespace Noox\Http\Controllers\CMS;

use Noox\Models\User;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Return the all users
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('cms.users');
    }

    /**
     * Return the all reported users
     * 
     * @return Illuminate\Http\Response
     */
    public function reported()
    {
        return view('cms.users_reported');
    }

    /**
     * Return the all users by ranking.
     * 
     * @return Illuminate\Http\Response
     */
    public function ranking()
    {
        return view('cms.users_ranking');
    }

    /**
     * View the details of the requested data.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function view($id)
    {
        if (! $user = User::find($id)) {
            abort(404);
        }

        return $user;
    }
}
