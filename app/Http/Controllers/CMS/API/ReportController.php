<?php

namespace Noox\Http\Controllers\CMS\API;

use Datatables;
use Noox\Models\Report;
use Noox\Models\ReportStatus;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;
use Noox\Notifications\NewsReportApprovedNotification;

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

    public function update(Request $request, $id)
    {
        if (! $report = Report::with(['status'])->find($id)) {
            return response(['message' => 'Report not found.'], 422);
        }
        if ($report->status->name === 'solved' || $report->status->name === 'approved') {
            return response(['message' => 'The current report status is already final.'], 422);
        }
        if ($report->status->name === 'investigating' || $report->status->name === 'closed') {
            if ($request->input('status') === 'open') {
                return response(['message' => 'Not allowed to update to this status.'], 403);
            }
        }
        if (! $newStatusId = ReportStatus::getId($request->input('status'))) {
            return response(['message' => 'Invalid status name.'], 422);
        }

        $report->status_id = $newStatusId;
        $report->save();

        if (($report->reportable_type == 'news') && ($report->reporter->getSetting('report_approved_notif') == '1')) {
           $report->reporter->notify(new NewsReportApprovedNotification($report->reportable));
        }

        return response(['message' => 'Report status successfully updated.']);
    }
}
