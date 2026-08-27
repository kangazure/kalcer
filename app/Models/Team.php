<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends BaseModel
{
    protected $table = 'team';

    protected $fillable = [
        'user_id', 'name', 'position', 'department', 'photo_url', 'bio',
        'linkedin_url', 'email', 'sort_order', 'is_management', 'is_active',
    ];

    protected $casts = [
        'is_management' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeManagement($query)
    {
        return $query->where('is_management', true);
    }
}
