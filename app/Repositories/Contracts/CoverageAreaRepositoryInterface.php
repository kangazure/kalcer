<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CoverageAreaRepositoryInterface extends BaseRepositoryInterface
{
    public function checkByCoordinate(float $lat, float $lng): Collection;

    public function allActiveForMap(): Collection;
}
