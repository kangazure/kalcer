<?php

namespace App\Repositories\Eloquent;

use App\Models\Portfolio;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PortfolioRepository extends BaseRepository implements PortfolioRepositoryInterface
{
    protected array $searchableColumns = ['title', 'client_name', 'summary', 'description'];

    protected array $filterableColumns = ['category', 'is_featured', 'is_published', 'project_year'];

    public function __construct(Portfolio $model)
    {
        $this->model = $model;
    }

    public function featured(int $limit = 6): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->featured()
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function byCategory(string $category): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->where('category', $category)
            ->orderBy('sort_order')
            ->get();
    }
}
