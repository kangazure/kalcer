<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends BaseModel
{
    protected $table = 'contact';

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'source', 'address',
        'latitude', 'longitude', 'status', 'assigned_to', 'handled_at',
        'notes', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'handled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
