<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CareerRepositoryInterface extends BaseRepositoryInterface
{
    public function openPositions(int $perPage = 10): LengthAwarePaginator;

    public function findOpenBySlug(string $slug): ?Model;

    public function submitApplication(int $careerId, array $applicationData): Model;

    public function applicationsForCareer(int $careerId): Collection;
}
