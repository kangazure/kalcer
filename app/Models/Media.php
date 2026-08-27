<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'uploader_id', 'bucket', 'storage_path', 'public_url', 'file_name',
        'original_name', 'mime_type', 'type', 'size_bytes', 'width', 'height',
        'duration_seconds', 'alt_text', 'caption', 'collection_name',
        'model_type', 'model_id', 'sort_order',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Relasi polymorphic manual — model_type berisi nama kelas singkat
     * (mis. 'Post', 'Gallery'), bukan FQCN. Map ke FQCN di accessor.
     */
    public function model(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
