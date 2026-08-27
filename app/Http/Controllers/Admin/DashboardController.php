<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\NetworkMonitor;
use App\Models\Post;
use App\Models\TroubleReport;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_posts' => Post::query()->count(),
            'new_contacts' => Contact::query()->where('status', 'new')->count(),
            'pending_applications' => JobApplication::query()->where('status', 'submitted')->count(),
            'open_trouble_reports' => TroubleReport::query()->whereIn('status', ['open', 'investigating'])->count(),
        ];

        $visitorChartData = Analytics::query()
            ->select(DB::raw('metric_date as date'), DB::raw('sum(unique_visitors) as visitors'))
            ->where('metric_date', '>=', now()->subDays(30))
            ->groupBy('metric_date')
            ->orderBy('metric_date')
            ->get();

        return view('admin.dashboard.index', [
            'stats' => $stats,
            'visitorChartData' => $visitorChartData,
            'networkNodes' => NetworkMonitor::query()->orderBy('node_name')->limit(6)->get(),
            'recentPosts' => Post::query()->latest()->limit(5)->get(),
            'recentContacts' => Contact::query()->latest()->limit(5)->get(),
        ]);
    }
}
