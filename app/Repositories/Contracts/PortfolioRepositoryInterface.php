<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PortfolioRepositoryInterface extends BaseRepositoryInterface
{
    public function featured(int $limit = 6): Collection;

    public function byCategory(string $category): Collection;
}
