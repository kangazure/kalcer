<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends BaseModel
{
    protected $table = 'banner';

    protected $fillable = [
        'title', 'position', 'image_url', 'image_url_mobile', 'link_url',
        'link_target', 'alt_text', 'starts_at', 'ends_at', 'is_active',
        'sort_order', 'created_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'click_count' => 'integer',
        'impression_count' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActiveOfPosition($query, string $position)
    {
        return $query->where('position', $position)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order');
    }
}
