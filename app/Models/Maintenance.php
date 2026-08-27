<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends BaseModel
{
    protected $table = 'maintenance';

    protected $fillable = [
        'title', 'description', 'affected_areas', 'affected_node_ids',
        'status', 'scheduled_start', 'scheduled_end', 'actual_start',
        'actual_end', 'created_by',
    ];

    protected $casts = [
        'affected_areas' => 'array',
        'affected_node_ids' => 'array',
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUpcomingOrOngoing($query)
    {
        return $query->whereIn('status', ['scheduled', 'ongoing']);
    }

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
}
