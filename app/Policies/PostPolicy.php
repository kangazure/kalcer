<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * Policy otorisasi granular untuk artikel blog. Super Admin sudah
 * otomatis lolos seluruh ability via Gate::before() di AuthServiceProvider,
 * jadi policy ini fokus pada aturan untuk role Admin/Editor/Marketing.
 */
class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRoleSlug(['super_admin', 'admin', 'editor', 'marketing']);
    }

    public function view(User $user, Post $post): bool
    {
        if ($user->hasRoleSlug(['super_admin', 'admin'])) {
            return true;
        }

        // Editor/Marketing hanya bisa melihat draft milik sendiri,
        // tapi semua artikel published tetap bisa dilihat siapa saja
        // (validasi published sudah ditangani di level controller publik).
        return $post->status === 'published' || $post->author_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionSlug('posts.create');
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->hasRoleSlug(['super_admin', 'admin'])) {
            return true;
        }

        return $user->hasPermissionSlug('posts.update') && $post->author_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasRoleSlug(['super_admin', 'admin'])
            || ($user->hasPermissionSlug('posts.delete') && $post->author_id === $user->id);
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->hasPermissionSlug('posts.publish');
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->hasRoleSlug(['super_admin', 'admin']);
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRoleSlug('super_admin');
    }
}
