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
     * Return the dashboard
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users');
    }

    /**
     * View the profile of the requested admin. Return current authenticated admin profile by default.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function profile($id = null)
    {
        if (! $user = User::find($id)) {
            # code...
        }

        try {
            $this->authorize('profile', $user);
            echo "this is your profile<br>";
            var_dump($user);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('admin.profile');
        }
    }
}
