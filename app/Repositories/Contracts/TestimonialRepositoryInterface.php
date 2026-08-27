<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TestimonialRepositoryInterface extends BaseRepositoryInterface
{
    public function featured(int $limit = 8): Collection;

    public function published(int $limit = 20): Collection;
}
