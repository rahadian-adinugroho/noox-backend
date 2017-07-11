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
            var_dump($user);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('admin.profile');
        }
    }

    /**
     * View admin creation page.
     *
     * @return Illuminate\Http\Response
     */
    public function viewCreate()
    {
        $this->authorize('create', Admin::class);
        return view('cms.create_admin');
    }

    /**
     * Process the new administrator data.
     *
     * @param  Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Admin::class);

        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6'
        ]);

        $data = array_merge($request->only(['name', 'email']), ['role' => 1, 'password' => bcrypt($request->input('password'), ['rounds' => 12])]);
        Admin::create($data);

        $request->session()->flash('flash_notification', 'Administrator successfully created!');

        return redirect()->route('admins');
    }
}
