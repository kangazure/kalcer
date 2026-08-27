<?php

namespace App\Models;

use Illuminate\Support\Str;

class Subscriber extends BaseModel
{
    protected $table = 'subscriber';

    public $timestamps = false;

    protected $fillable = [
        'email', 'name', 'is_verified', 'verification_token',
        'unsubscribe_token', 'subscribed_at', 'unsubscribed_at', 'source',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Subscriber $subscriber) {
            $subscriber->verification_token ??= Str::random(48);
            $subscriber->unsubscribe_token ??= Str::random(48);
        });
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)->whereNull('unsubscribed_at');
    }
}
