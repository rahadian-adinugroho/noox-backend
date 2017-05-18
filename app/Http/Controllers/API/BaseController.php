<?php

namespace Noox\Http\Controllers\API;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Barryvdh\Cors\HandleCors::class);
    }
    
    use Helpers, ValidatesRequests;
}