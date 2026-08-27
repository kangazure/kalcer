<?php

namespace App\Repositories\Eloquent;

use App\Models\Testimonial;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TestimonialRepository extends BaseRepository implements TestimonialRepositoryInterface
{
    protected array $searchableColumns = ['customer_name', 'content'];

    protected array $filterableColumns = ['is_featured', 'is_published', 'package_id', 'rating'];

    public function __construct(Testimonial $model)
    {
        $this->model = $model;
    }

    public function featured(int $limit = 8): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->featured()
            ->with('package:id,name')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function published(int $limit = 20): Collection
    {
        return $this->model->newQuery()
            ->published()
            ->with('package:id,name')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}
