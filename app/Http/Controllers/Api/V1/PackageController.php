<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PackageResource;
use App\Repositories\Contracts\PackageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackageController extends ApiController
{
    public function __construct(protected PackageRepositoryInterface $packages)
    {
    }

    public function index(Request $request): JsonResponse
    {
        if ($category = $request->query('category')) {
            return $this->success(PackageResource::collection($this->packages->activeByCategory($category)));
        }

        $grouped = $this->packages->allActiveGroupedByCategory();

        return $this->success($grouped->map(fn ($items) => PackageResource::collection($items)));
    }

    public function show(string $slug): JsonResponse
    {
        $package = $this->packages->findBySlug($slug);

        if (! $package) {
            return $this->notFound('Paket tidak ditemukan.');
        }

        return $this->success(new PackageResource($package));
    }

    public function popular(): JsonResponse
    {
        return $this->success(PackageResource::collection($this->packages->popular(3)));
    }
}
