<?php

namespace App\Models;

class Visitor extends BaseModel
{
    protected $table = 'visitor';

    public $timestamps = false;

    protected $fillable = [
        'session_id', 'ip_address', 'user_agent', 'referrer', 'landing_page',
        'country', 'city', 'device_type', 'browser', 'os', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
