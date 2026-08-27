<?php

namespace App\Models;

class Slider extends BaseModel
{
    protected $table = 'slider';

    protected $fillable = [
        'title', 'subtitle', 'description', 'image_url', 'video_url',
        'cta_label', 'cta_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
