<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NetworkMonitor extends BaseModel
{
    protected $table = 'network_monitor';

    protected $fillable = [
        'node_name', 'node_type', 'ip_address', 'latitude', 'longitude',
        'status', 'bandwidth_capacity_mbps', 'bandwidth_usage_mbps',
        'latency_ms', 'packet_loss_percent', 'uptime_percent',
        'last_checked_at', 'last_down_at', 'parent_node_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'bandwidth_capacity_mbps' => 'integer',
        'bandwidth_usage_mbps' => 'decimal:2',
        'latency_ms' => 'decimal:2',
        'packet_loss_percent' => 'decimal:2',
        'uptime_percent' => 'decimal:2',
        'last_checked_at' => 'datetime',
        'last_down_at' => 'datetime',
    ];

    public function parentNode(): BelongsTo
    {
        return $this->belongsTo(NetworkMonitor::class, 'parent_node_id');
    }

    public function childNodes(): HasMany
    {
        return $this->hasMany(NetworkMonitor::class, 'parent_node_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(NetworkMonitorHistory::class, 'node_id');
    }

    public function coverageAreas(): HasMany
    {
        return $this->hasMany(CoverageArea::class, 'pop_id');
    }

    public function troubleReports(): HasMany
    {
        return $this->hasMany(TroubleReport::class, 'node_id');
    }

    public function getUsagePercentAttribute(): ?float
    {
        if (! $this->bandwidth_capacity_mbps || $this->bandwidth_capacity_mbps == 0) {
            return null;
        }

        return round(((float) $this->bandwidth_usage_mbps / $this->bandwidth_capacity_mbps) * 100, 2);
    }

    public const STATUS_ONLINE = 'online';
    public const STATUS_OFFLINE = 'offline';
    public const STATUS_DEGRADED = 'degraded';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_UNKNOWN = 'unknown';
}
