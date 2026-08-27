<?php

namespace App\Services;

use App\Models\Media;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\UploadedFile;

/**
 * Menjembatani upload file ke Supabase Storage dengan pencatatan metadata
 * di tabel media. Dipakai oleh Media Manager admin dan modul lain yang
 * butuh upload gambar/dokumen (artikel, galeri, CV pelamar).
 */
class MediaService
{
    public function __construct(protected SupabaseStorageService $storage)
    {
    }

    public function uploadImage(UploadedFile $file, string $bucket = 'media', ?int $uploaderId = null, string $directory = ''): Media
    {
        $result = $this->storage->upload($file, $bucket, $directory);

        [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

        return Media::query()->create([
            'uploader_id' => $uploaderId,
            'bucket' => $bucket,
            'storage_path' => $result['path'],
            'public_url' => $result['public_url'],
            'file_name' => basename($result['path']),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'type' => 'image',
            'size_bytes' => $file->getSize(),
            'width' => $width,
            'height' => $height,
        ]);
    }

    public function uploadDocument(UploadedFile $file, string $bucket = 'documents', string $directory = ''): Media
    {
        $result = $this->storage->upload($file, $bucket, $directory);

        return Media::query()->create([
            'bucket' => $bucket,
            'storage_path' => $result['path'],
            'public_url' => $bucket === 'documents' ? null : $result['public_url'],
            'file_name' => basename($result['path']),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'type' => 'document',
            'size_bytes' => $file->getSize(),
        ]);
    }

    public function delete(Media $media): bool
    {
        $this->storage->delete($media->bucket, $media->storage_path);

        return (bool) $media->delete();
    }

    public function getSignedUrl(Media $media, int $expiresInSeconds = 3600): string
    {
        return $this->storage->getSignedUrl($media->bucket, $media->storage_path, $expiresInSeconds);
    }
}
