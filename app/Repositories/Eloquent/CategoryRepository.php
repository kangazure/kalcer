<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected array $searchableColumns = ['name', 'description'];

    protected array $filterableColumns = ['parent_id', 'is_active'];

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function activeTree(): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();
    }

    public function withPostCount(): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('sort_order')
            ->get();
    }
}
