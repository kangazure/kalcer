<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services.index', [
            'services' => Service::query()->active()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::query()->active()->where('slug', $slug)->with('packages')->first();

        if (! $service) {
            throw new NotFoundHttpException('Layanan tidak ditemukan.');
        }

        return view('pages.services.show', [
            'service' => $service,
            'relatedServices' => Service::query()->active()->where('id', '!=', $service->id)->limit(3)->get(),
        ]);
    }
}
