<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{

    public function index()
    {
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(10);

        return view('log.index', compact('logs'));
    }
}
