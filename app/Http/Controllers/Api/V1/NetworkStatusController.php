<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Public\NetworkStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NetworkStatusController extends ApiController
{
    public function __construct(protected NetworkStatusService $networkStatusService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->success($this->networkStatusService->publicStatusSummary());
    }

    public function bandwidthChart(int $nodeId, Request $request): JsonResponse
    {
        $hours = (int) $request->query('hours', 24);

        return $this->success($this->networkStatusService->bandwidthChart($nodeId, $hours));
    }

    public function reportTrouble(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:150'],
            'reporter_phone' => ['required', 'string', 'max:20'],
            'reporter_email' => ['nullable', 'email'],
            'region_name' => ['required', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $report = $this->networkStatusService->submitTroubleReport(array_merge($validated, ['severity' => 'medium']), $request);

        return $this->created(['report_id' => $report->id], 'Laporan gangguan berhasil dikirim.');
    }
}
