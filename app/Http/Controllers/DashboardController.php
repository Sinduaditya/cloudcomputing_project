<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Download;
use App\Models\ScheduledTask;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Return the admin dashboard view with stats
        return view('dashboard.index');
    }

    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
}
