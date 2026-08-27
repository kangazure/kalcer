<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements AuthenticatableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'uuid', 'auth_user_id', 'role_id', 'name', 'email', 'phone',
        'avatar_url', 'bio', 'position', 'status', 'email_verified_at',
        'last_login_at', 'last_login_ip', 'two_factor_enabled', 'created_by',
    ];

    protected $hidden = [
        'remember_token', 'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->uuid ??= (string) \Illuminate\Support\Str::uuid();
        });
    }

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function teamProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Team::class, 'user_id');
    }

    // ------------------------------------------------------------------
    // RBAC Helpers
    // ------------------------------------------------------------------

    /**
     * Cek apakah user memiliki salah satu role slug yang diberikan.
     *
     * @param string|array<int, string> $slugs
     */
    public function hasRoleSlug(string|array $slugs): bool
    {
        if (! $this->role) {
            return false;
        }

        $slugs = is_array($slugs) ? $slugs : [$slugs];

        return in_array($this->role->slug, $slugs, true);
    }

    /**
     * Cek apakah user memiliki permission tertentu melalui role-nya.
     */
    public function hasPermissionSlug(string $slug): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->permissions()->where('slug', $slug)->exists();
    }

    // ------------------------------------------------------------------
    // JWTSubject Implementation (tymon/jwt-auth)
    // ------------------------------------------------------------------
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role_slug' => $this->role?->slug,
            'uuid' => $this->uuid,
        ];
    }
}
