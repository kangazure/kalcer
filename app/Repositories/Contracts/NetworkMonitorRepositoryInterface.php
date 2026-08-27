<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NetworkMonitorRepositoryInterface extends BaseRepositoryInterface
{
    public function statusSummary(): Collection;

    public function bandwidthChartData(int $nodeId, int $hours = 24): Collection;

    public function recordHistory(int $nodeId, array $metrics): void;

    public function updateNodeStatus(int $nodeId, string $status, array $metrics = []): void;
}
