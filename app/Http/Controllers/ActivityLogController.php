<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('activity-logs.index', compact('logs'));
    }
}
