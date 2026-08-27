<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\JobApplicationRequest;
use App\Services\Public\CareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CareerController extends Controller
{
    public function __construct(protected CareerService $careerService)
    {
    }

    public function index(): View
    {
        return view('pages.career.index', [
            'careers' => $this->careerService->listOpenPositions(10),
        ]);
    }

    public function show(string $slug): View
    {
        $career = $this->careerService->findBySlug($slug);

        if (! $career) {
            throw new NotFoundHttpException('Lowongan tidak ditemukan atau sudah ditutup.');
        }

        return view('pages.career.show', ['career' => $career]);
    }

    public function apply(int $careerId, JobApplicationRequest $request): JsonResponse
    {
        $this->careerService->applyForPosition(
            careerId: $careerId,
            data: $request->validated(),
            resume: $request->file('resume'),
            request: $request,
        );

        return response()->json([
            'success' => true,
            'message' => 'Lamaran Anda berhasil dikirim. Tim kami akan menghubungi Anda jika lolos seleksi awal.',
        ], 201);
    }
}
