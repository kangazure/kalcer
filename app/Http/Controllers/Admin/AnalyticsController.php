<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $totalVisitors = Analytics::query()->where('metric_date', '>=', now()->subDays(30))->sum('unique_visitors');

        $topPage = Analytics::query()
            ->where('metric_date', '>=', now()->subDays(30))
            ->select('page_path', DB::raw('sum(page_views) as views'))
            ->groupBy('page_path')
            ->orderByDesc('views')
            ->first();

        $deviceCounts = Visitor::query()
            ->where('visited_at', '>=', now()->subDays(30))
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        return view('admin.analytics.index', [
            'stats' => [
                'total_visitors' => $totalVisitors,
                'avg_daily' => round($totalVisitors / 30),
                'top_page' => $topPage?->page_path ?? '-',
                'bounce_rate' => 0,
            ],
            'trafficChartData' => Analytics::query()
                ->select('metric_date as date', DB::raw('sum(page_views) as views'))
                ->where('metric_date', '>=', now()->subDays(30))
                ->groupBy('metric_date')
                ->orderBy('metric_date')
                ->get(),
            'topPages' => Analytics::query()
                ->select('page_path', DB::raw('sum(page_views) as page_views'))
                ->where('metric_date', '>=', now()->subDays(30))
                ->groupBy('page_path')
                ->orderByDesc('page_views')
                ->limit(10)
                ->get(),
            'deviceChartData' => [
                'desktop' => $deviceCounts['desktop'] ?? 0,
                'mobile' => $deviceCounts['mobile'] ?? 0,
                'tablet' => $deviceCounts['tablet'] ?? 0,
            ],
        ]);
    }
}
