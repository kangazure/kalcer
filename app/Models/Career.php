<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Career extends BaseModel
{
    use HasSlug;

    protected $table = 'career';

    protected $fillable = [
        'title', 'slug', 'department', 'location', 'job_type', 'description',
        'requirements', 'responsibilities', 'benefits', 'salary_min',
        'salary_max', 'salary_is_negotiable', 'vacancy_count', 'is_active',
        'closes_at', 'created_by',
    ];

    protected $casts = [
        'requirements' => 'array',
        'responsibilities' => 'array',
        'benefits' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'salary_is_negotiable' => 'boolean',
        'vacancy_count' => 'integer',
        'is_active' => 'boolean',
        'closes_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'career_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>', now());
            });
    }

    public function getIsClosedAttribute(): bool
    {
        return ! $this->is_active || ($this->closes_at && $this->closes_at->isPast());
    }
}
