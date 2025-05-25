<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::where('user_id', auth()->id())
            ->orderBy('timestamp', 'desc')
            ->get();
        return response()->json($logs);
    }
}
