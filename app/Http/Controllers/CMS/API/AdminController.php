<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use JWTAuth;
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
}
