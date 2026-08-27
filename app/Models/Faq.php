<?php

namespace App\Models;

class Faq extends BaseModel
{
    protected $table = 'faq';

    protected $fillable = [
        'category', 'question', 'answer', 'sort_order', 'is_active', 'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'view_count' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
