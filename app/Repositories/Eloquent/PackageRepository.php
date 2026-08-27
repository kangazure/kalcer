<?php

namespace App\Repositories\Eloquent;

use App\Models\Package;
use App\Repositories\Contracts\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    protected array $searchableColumns = ['name'];

    protected array $filterableColumns = ['category', 'service_id', 'is_active', 'is_popular'];

    public function __construct(Package $model)
    {
        $this->model = $model;
    }

    public function activeByCategory(string $category): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->ofCategory($category)
            ->with('service:id,name,slug')
            ->orderBy('sort_order')
            ->orderBy('speed_mbps_download')
            ->get();
    }

    public function popular(int $limit = 3): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->where('is_popular', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function allActiveGroupedByCategory(): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->with('service:id,name,slug')
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
    }
}
