<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends BaseModel
{
    protected $table = 'roles';

    protected $fillable = [
        'name', 'slug', 'description', 'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Konstanta slug role, dipakai di seluruh aplikasi agar tidak ada
     * magic string yang tersebar (super_admin, admin, editor, dst).
     */
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const EDITOR = 'editor';
    public const MARKETING = 'marketing';
    public const OPERATOR = 'operator';
}
