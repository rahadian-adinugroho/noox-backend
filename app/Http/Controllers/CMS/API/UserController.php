<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use Noox\Models\User;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Noox\Http\Middleware\JWTMultiAuth::class);
    }

    /**
     * Return the list of users.
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at']);

        return Datatables::of($users)->addColumn('action', function ($user) {
                return '<a href="'.route('cms.user.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Return the list of reported users.
     * 
     * @return Illuminate\Http\Response
     */
    public function reported()
    {
        $users = User::select(['id', 'name', 'email'])->withCount('reports')->has('reports');

        return Datatables::of($users)->addColumn('action', function ($user) {
                return '<a href="'.route('cms.user.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Restore the list of user reports.
     * 
     * @param  int $userId
     * @return Illuminate\Http\Response
     */
    public function reports($userId)
    {
        if (! $user = User::find($userId)) {
            return response(['message' => 'Comment not found.'], 422);
        }

        $reports = $user->reports()->select(['id', 'reporter_id', \DB::raw('LEFT(`content`, 100) as `content`'), 'status_id', 'reportable_type', 'created_at'])
        ->with(['reporter' => function($q){
            $q->select(['id', 'name']);
        }, 'status']);

        return Datatables::of($reports)->addColumn('action', function ($report) {
                return '<a href="'.route('cms.report.details', [$report->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Return users by ranking.
     * 
     * @return Illuminate\Http\Response
     */
    public function ranking()
    {
        $users = User::select(['id', 'name', 'email', 'experience']);

        return Datatables::of($users)
            ->addColumn('level', function ($user) {
                return $user->getLevel();
            })
            ->addColumn('action', function ($user) {
                return '<a href="'.route('cms.user.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }

    /**
     * Update the user.
     * 
     * @param  Illuminate\Http\Request
     * @param  int
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! $user = User::find($id)) {
            return response(['message' => 'User not found.'], 422);
        }

        $validation = [
            'name'     => 'required|min:3',
            'password' => 'nullable|min:6',
            'gender'   => 'required|regex:/^[mf]$/u',
            'birthday' => 'required|before:' . \Carbon\Carbon::now()->format('Y-m-d')
        ];
        if ($request->input('email') != $user->email) {
            $validation = array_merge($validation, ['email' => 'required|email|unique:users']);
        }
        $this->validate($request, $validation);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->birthday = $request->input('birthday');

        if (! is_null($request->input('password'))) {
            $user->password = bcrypt($request->input('password'), ['rounds' => 12]);
        }
        $user->save();

        return response(['message' => 'User data successfully updated.']);
    }
}

