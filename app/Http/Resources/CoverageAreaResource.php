<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CoverageArea */
class CoverageAreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'region_name' => $this->region_name,
            'district' => $this->district,
            'regency' => $this->regency,
            'coordinates' => ['latitude' => (float) $this->center_latitude, 'longitude' => (float) $this->center_longitude],
            'radius_meters' => $this->radius_meters,
            'coverage_status' => $this->coverage_status,
            'polygon_geojson' => $this->polygon_geojson,
        ];
    }
}
