<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoverageArea extends BaseModel
{
    protected $table = 'coverage_area';

    protected $fillable = [
        'region_name', 'district', 'regency', 'province', 'center_latitude',
        'center_longitude', 'radius_meters', 'polygon_geojson',
        'coverage_status', 'pop_id', 'is_active',
    ];

    protected $casts = [
        'center_latitude' => 'decimal:6',
        'center_longitude' => 'decimal:6',
        'radius_meters' => 'integer',
        'polygon_geojson' => 'array',
        'is_active' => 'boolean',
    ];

    public function pop(): BelongsTo
    {
        return $this->belongsTo(NetworkMonitor::class, 'pop_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PLANNED = 'planned';
}
