<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use JWTAuth;
use Auth;
use Noox\Models\Admin;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Noox\Http\Middleware\JWTMultiAuth::class);
    }

    /**
     * Return the list of admins.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Admin::class);

        // get the id to filter the result
        $adminId = JWTAuth::getPayload()->get('sub');

        // retrieve the admins except the super admins (including the requester)
        $admins = Admin::select(['id', 'name', 'email', 'created_at', 'updated_at'])
        ->where('role', 1);

        return Datatables::of($admins)->addColumn('action', function ($admin) {
                return '<a href="'.route('admin.profile', [$admin->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    public function update(Request $request, $id=null)
    {
        if (is_null($id)) {
            $admin = Auth::user();
        } else {
            if (! $admin = Admin::find($id)) {
                return response(['message' => 'Administrator ID not found.'], 422);
            }
        }

        $this->authorize('update', $admin);

        $validation = [
            'name' => 'required|min:3',
            'password' => 'nullable|min:6'
        ];
        if ($request->input('email') != $admin->email) {
            $validation = array_merge($validation, ['email' => 'required|email|unique:admins']);
        }
        $this->validate($request, $validation);

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');

        if (! is_null($request->input('password'))) {
            $admin->password = bcrypt($request->input('password'), ['rounds' => 12]);
        }
        $admin->save();

        return response(['message' => 'Administrator data successfully updated.']);
    }
}
