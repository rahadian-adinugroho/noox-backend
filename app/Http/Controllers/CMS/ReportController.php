<?php

namespace Noox\Http\Controllers\CMS;

use Noox\Models\Report;
use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * View reports table.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('cms.reports');
    }

    /**
     * View report details.
     *
     * @param int $id
     * @return Illuminate\Http\Response
     */
    public function view($id)
    {
        if (! $data = Report::with(['reporter', 'status'])->find($id)) {
            abort(404);
        }
        return view('cms.report_details', compact('data'));
    }
}
