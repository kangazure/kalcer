<?php

namespace App\Services\Public;

use App\Events\NewJobApplicationSubmitted;
use App\Models\Career;
use App\Models\JobApplication;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Business logic untuk halaman karir publik: listing lowongan terbuka,
 * detail lowongan, dan proses submit lamaran kerja (termasuk upload CV).
 */
class CareerService
{
    public function __construct(protected MediaService $mediaService)
    {
    }

    public function listOpenPositions(int $perPage = 10): LengthAwarePaginator
    {
        return Career::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>=', now());
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Career
    {
        return Career::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>=', now());
            })
            ->first();
    }

    public function applyForPosition(int $careerId, array $data, ?UploadedFile $resume, Request $request): JobApplication
    {
        $resumeMedia = $resume ? $this->mediaService->uploadDocument($resume, 'documents', 'resumes') : null;

        $application = JobApplication::query()->create([
            'career_id' => $careerId,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_media_id' => $resumeMedia?->id,
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'expected_salary' => $data['expected_salary'] ?? null,
            'status' => 'submitted',
            'ip_address' => $request->ip(),
        ]);

        event(new NewJobApplicationSubmitted($application));

        return $application;
    }
}
