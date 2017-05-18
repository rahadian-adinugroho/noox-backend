<?php

namespace Noox\Http\Controllers;

use Auth;
use Noox\Models\Admin;
use Illuminate\Http\Request;

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
        return view('admin.dashboard');
    }

    /**
     * View the profile of the requested admin. Return current authenticated admin profile by default.
     * 
     * @param  integer $id
     * @return Illuminate\Http\Response
     */
    public function view($id = null)
    {
        if (is_null($id)) {
            $user = Auth::user();
        } else {
            $user = Admin::find($id);
        }

        try {
            $this->authorize('view', $user);
            echo "this is your profile<br>";
            var_dump($user);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('admin.profile');
        }
    }
}
