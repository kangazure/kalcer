<?php

namespace App\Models;

class Announcement extends BaseModel
{
    protected $table = 'announcement';

    protected $fillable = [
        'title', 'content', 'severity', 'is_active', 'starts_at', 'ends_at', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public const SEVERITY_INFO = 'info';
    public const SEVERITY_WARNING = 'warning';
    public const SEVERITY_CRITICAL = 'critical';
}
