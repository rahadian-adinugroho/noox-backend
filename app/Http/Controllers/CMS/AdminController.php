<?php

namespace Noox\Http\Controllers\CMS;

use Auth;
use Noox\Models\Admin;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class AdminController extends Controller
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
        return view('cms.dashboard');
    }

    /**
     * Return the dashboard
     * 
     * @return Illuminate\Http\Response
     */
    public function adminList()
    {
        $this->authorize('view', Admin::class);
        return view('cms.admins');
    }

    /**
     * View the profile of the requested admin. Return current authenticated admin profile by default.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function profile($id = null)
    {
        if (is_null($id)) {
            $user = Auth::user();
        } else {
            $user = Admin::findOrFail($id);
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
