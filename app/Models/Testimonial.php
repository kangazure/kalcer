<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends BaseModel
{
    protected $table = 'testimonial';

    protected $fillable = [
        'customer_name', 'customer_role', 'customer_photo_url', 'package_id',
        'rating', 'content', 'is_featured', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
