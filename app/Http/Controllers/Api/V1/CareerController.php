<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Public\JobApplicationRequest;
use App\Http\Resources\CareerResource;
use App\Services\Public\CareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends ApiController
{
    public function __construct(protected CareerService $careerService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $positions = $this->careerService->listOpenPositions((int) $request->query('per_page', 10));

        return $this->paginated($positions, CareerResource::class);
    }

    public function show(string $slug): JsonResponse
    {
        $career = $this->careerService->findBySlug($slug);

        if (! $career) {
            return $this->notFound('Lowongan tidak ditemukan atau sudah ditutup.');
        }

        return $this->success(new CareerResource($career));
    }

    public function apply(int $careerId, JobApplicationRequest $request): JsonResponse
    {
        $this->careerService->applyForPosition(
            careerId: $careerId,
            data: $request->validated(),
            resume: $request->file('resume'),
            request: $request,
        );

        return $this->created(message: 'Lamaran Anda berhasil dikirim.');
    }
}
