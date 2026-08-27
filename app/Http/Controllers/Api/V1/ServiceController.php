<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends ApiController
{
    public function index(): JsonResponse
    {
        $services = Service::query()->active()->orderBy('sort_order')->get();

        return $this->success(ServiceResource::collection($services));
    }

    public function show(string $slug): JsonResponse
    {
        $service = Service::query()->active()->where('slug', $slug)->with('packages')->first();

        if (! $service) {
            return $this->notFound('Layanan tidak ditemukan.');
        }

        return $this->success(new ServiceResource($service));
    }
}
