<?php

namespace App\Models;

class Popup extends BaseModel
{
    protected $table = 'popup';

    protected $fillable = [
        'title', 'content', 'image_url', 'link_url', 'link_label',
        'display_rule', 'show_delay_ms', 'starts_at', 'ends_at',
        'is_active', 'created_by',
    ];

    protected $casts = [
        'show_delay_ms' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeCurrentlyActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}
