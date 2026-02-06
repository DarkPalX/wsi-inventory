<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use \App\Models\ActivityLog;


use Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    	$logs = ActivityLog::where('log_by', auth()->id())->orderBy('id','desc')->paginate(15);

        return view('admin.dashboard.index',compact('logs'));
    }
}
