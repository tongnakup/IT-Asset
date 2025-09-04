<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('activity_logs.index', compact('logs'));
    }
}