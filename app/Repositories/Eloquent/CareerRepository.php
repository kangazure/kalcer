<?php

namespace App\Repositories\Eloquent;

use App\Models\Career;
use App\Models\JobApplication;
use App\Repositories\Contracts\CareerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CareerRepository extends BaseRepository implements CareerRepositoryInterface
{
    protected array $searchableColumns = ['title', 'department', 'description'];

    protected array $filterableColumns = ['department', 'job_type', 'is_active', 'location'];

    public function __construct(Career $model)
    {
        $this->model = $model;
    }

    public function openPositions(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->open()
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findOpenBySlug(string $slug): ?Model
    {
        return $this->model->newQuery()
            ->open()
            ->where('slug', $slug)
            ->first();
    }

    public function submitApplication(int $careerId, array $applicationData): Model
    {
        return JobApplication::query()->create(array_merge($applicationData, [
            'career_id' => $careerId,
            'status' => JobApplication::STATUS_SUBMITTED,
        ]));
    }

    public function applicationsForCareer(int $careerId): Collection
    {
        return JobApplication::query()
            ->where('career_id', $careerId)
            ->with('resume')
            ->latest()
            ->get();
    }
}
