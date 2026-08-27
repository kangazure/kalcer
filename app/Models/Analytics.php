<?php

namespace App\Models;

class Analytics extends BaseModel
{
    protected $table = 'analytics';

    public $timestamps = false;

    protected $fillable = [
        'metric_date', 'page_path', 'page_views', 'unique_visitors',
        'avg_duration_seconds', 'bounce_rate',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'page_views' => 'integer',
        'unique_visitors' => 'integer',
        'avg_duration_seconds' => 'integer',
        'bounce_rate' => 'decimal:2',
    ];
}
