<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CoverageCheckRequest;
use App\Services\Public\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CoverageController extends Controller
{
    public function __construct(protected ContactService $contactService)
    {
    }

    public function index(): View
    {
        return view('pages.coverage.index', [
            'areas' => $this->contactService->allCoverageAreasForMap(),
        ]);
    }

    public function check(CoverageCheckRequest $request): JsonResponse
    {
        $result = $this->contactService->checkCoverage(
            lat: (float) $request->validated('latitude'),
            lng: (float) $request->validated('longitude'),
            address: $request->validated('address'),
            request: $request,
        );

        return response()->json([
            'success' => true,
            'is_covered' => $result['is_covered'],
            'nearest_area' => $result['nearest_area'],
        ]);
    }
}
