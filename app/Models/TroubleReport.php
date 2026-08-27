<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TroubleReport extends BaseModel
{
    protected $table = 'trouble_report';

    protected $fillable = [
        'reporter_name', 'reporter_phone', 'reporter_email', 'customer_id_number',
        'node_id', 'region_name', 'title', 'description', 'severity', 'status',
        'assigned_to', 'resolution_notes', 'reported_at', 'resolved_at',
        'ip_address',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(NetworkMonitor::class, 'node_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'investigating']);
    }

    public const SEVERITY_LOW = 'low';
    public const SEVERITY_MEDIUM = 'medium';
    public const SEVERITY_HIGH = 'high';
    public const SEVERITY_CRITICAL = 'critical';

    public const STATUS_OPEN = 'open';
    public const STATUS_INVESTIGATING = 'investigating';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';
}
