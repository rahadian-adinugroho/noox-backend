<?php

namespace Noox\Http\Controllers\CMS;

use Illuminate\Http\Request;
use Noox\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Return the all news
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return view('cms.reports');
    }
}
