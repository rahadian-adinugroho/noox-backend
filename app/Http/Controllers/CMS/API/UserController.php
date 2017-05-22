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
                return '<a href="'.route('cms.users.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
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
                return '<a href="'.route('cms.users.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>'
                        .'<a href="'.route('cms.users.profile', [$user->id]).'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-edit"></i> View Reports</a>';
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
        $users = User::select(['id', 'name', 'email', 'level', 'xp']);

        return Datatables::of($users)->addColumn('action', function ($user) {
                return '<a href="'.route('cms.users.profile', [$user->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }
}

