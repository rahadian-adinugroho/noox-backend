<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use Noox\Models\Report;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Noox\Http\Middleware\JWTMultiAuth::class);
    }

    /**
     * Return the list of reports.
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::select(['id', 'reporter_id', \DB::raw('LEFT(`content`, 100) as `content`'), 'status_id', 'reportable_type', 'created_at'])
        ->with(['reporter' => function($q){
            $q->select(['id', 'name']);
        }, 'status']);

        return Datatables::of($reports)->addColumn('action', function ($report) {
                return '<a href="'.route('cms.report.details', [$report->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> View</a>';
            })
            ->make(true);
    }
}
