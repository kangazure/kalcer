<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Public\CoverageCheckRequest;
use App\Http\Resources\CoverageAreaResource;
use App\Services\Public\ContactService;
use Illuminate\Http\JsonResponse;

class CoverageController extends ApiController
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->success(CoverageAreaResource::collection($this->contactService->allCoverageAreasForMap()));
    }

    public function check(CoverageCheckRequest $request): JsonResponse
    {
        $result = $this->contactService->checkCoverage(
            lat: (float) $request->validated('latitude'),
            lng: (float) $request->validated('longitude'),
            address: $request->validated('address'),
            request: $request,
        );

        return $this->success(['is_covered' => $result['is_covered'], 'nearest_area' => $result['nearest_area']]);
    }
}
