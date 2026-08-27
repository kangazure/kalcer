<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Public\NetworkStatusService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $posts,
        protected NetworkStatusService $networkStatusService,
    ) {
    }

    public function index(): View
    {
        return view('pages.home', [
            'services' => Service::query()->active()->orderBy('sort_order')->get(),
            'popularPackages' => Package::query()->active()->where('is_popular', true)->orderBy('sort_order')->limit(3)->get(),
            'networkStatus' => $this->networkStatusService->publicStatusSummary()->take(4),
            'coverageAreas' => CoverageArea::query()->active()->orderBy('region_name')->get(),
            'testimonials' => Testimonial::query()->published()->featured()->orderBy('sort_order')->limit(8)->get(),
            'latestPosts' => $this->posts->recent(6),
        ]);
    }
}
