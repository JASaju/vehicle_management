<?php

namespace App\Http\Controllers;

use App\Support\SecurityLogReader;
use Illuminate\Contracts\View\View;

class SecurityLogController extends Controller
{
    public function index(SecurityLogReader $logs): View
    {
        return view('security-logs.index', $logs->latest());
    }
}
