<?php

namespace App\Repositories\Eloquent;

use App\Models\CoverageArea;
use App\Repositories\Contracts\CoverageAreaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CoverageAreaRepository extends BaseRepository implements CoverageAreaRepositoryInterface
{
    protected array $searchableColumns = ['region_name', 'district', 'regency'];

    protected array $filterableColumns = ['coverage_status', 'is_active', 'district'];

    public function __construct(CoverageArea $model)
    {
        $this->model = $model;
    }

    /**
     * Mencari wilayah jangkauan terdekat dari titik koordinat pengguna.
     * Memanggil stored function `check_coverage_by_point` di Postgres
     * (haversine formula) untuk performa lebih baik daripada menghitung
     * di level aplikasi untuk seluruh baris.
     */
    public function checkByCoordinate(float $lat, float $lng): Collection
    {
        $results = DB::connection('pgsql')->select(
            'select * from public.check_coverage_by_point(?, ?)',
            [$lat, $lng]
        );

        return collect($results)->map(fn ($row) => (object) [
            'area_id' => $row->area_id,
            'region_name' => $row->region_name,
            'coverage_status' => $row->coverage_status,
            'distance_meters' => (float) $row->distance_meters,
            'is_covered' => $row->coverage_status === CoverageArea::STATUS_AVAILABLE && $row->distance_meters <= 5000,
        ]);
    }

    public function allActiveForMap(): Collection
    {
        return $this->model->newQuery()
            ->active()
            ->select(['id', 'region_name', 'district', 'center_latitude', 'center_longitude', 'radius_meters', 'coverage_status', 'polygon_geojson'])
            ->get();
    }
}
