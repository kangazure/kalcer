<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $fillable = [
        'group_name', 'key', 'value', 'label', 'description', 'is_public', 'updated_by',
    ];

    protected $casts = [
        'value' => 'array',
        'is_public' => 'boolean',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeOfGroup($query, string $group)
    {
        return $query->where('group_name', $group);
    }
}
