<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkMonitorHistory extends BaseModel
{
    protected $table = 'network_monitor_history';

    public $timestamps = false;

    protected $fillable = [
        'node_id', 'bandwidth_usage_mbps', 'latency_ms', 'packet_loss_percent',
        'status', 'recorded_at',
    ];

    protected $casts = [
        'bandwidth_usage_mbps' => 'decimal:2',
        'latency_ms' => 'decimal:2',
        'packet_loss_percent' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(NetworkMonitor::class, 'node_id');
    }
}
