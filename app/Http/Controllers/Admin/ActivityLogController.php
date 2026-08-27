<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard.activity-log', [
            'logs' => ActivityLog::query()->with('user:id,name')->latest()->paginate(30),
        ]);
    }
}
