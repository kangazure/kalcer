<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PackageRepositoryInterface extends BaseRepositoryInterface
{
    public function activeByCategory(string $category): Collection;

    public function popular(int $limit = 3): Collection;

    public function allActiveGroupedByCategory(): Collection;
}
