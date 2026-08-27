<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Services\Public\NetworkStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NetworkStatusController extends Controller
{
    public function __construct(protected NetworkStatusService $networkStatusService)
    {
    }

    public function index(): View
    {
        return view('pages.network-status.index', [
            'nodes' => $this->networkStatusService->publicStatusSummary(),
            'maintenances' => Maintenance::query()->whereIn('status', ['scheduled', 'ongoing'])->orderBy('scheduled_start')->get(),
        ]);
    }

    public function statusJson(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->networkStatusService->publicStatusSummary(),
        ]);
    }

    public function submitTroubleReport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reporter_name' => ['required', 'string', 'max:150'],
            'reporter_phone' => ['required', 'string', 'max:20'],
            'reporter_email' => ['nullable', 'email'],
            'region_name' => ['required', 'string', 'max:150'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $this->networkStatusService->submitTroubleReport(
            array_merge($validated, ['severity' => 'medium']),
            $request,
        );

        return response()->json([
            'success' => true,
            'message' => 'Laporan gangguan Anda telah kami terima. Tim teknis akan segera menindaklanjuti.',
        ], 201);
    }
}
