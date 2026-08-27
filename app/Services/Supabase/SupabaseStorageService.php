<?php

namespace App\Services\Supabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Mengelola upload, hapus, dan signed URL file ke Supabase Storage.
 * Dipakai oleh App\Services\MediaService untuk seluruh modul yang
 * memerlukan upload gambar/dokumen (artikel, galeri, CV pelamar, dst).
 */
class SupabaseStorageService
{
    public function __construct(protected SupabaseClient $client)
    {
    }

    /**
     * Upload file ke bucket tertentu. Mengembalikan path penyimpanan
     * relatif dan public URL (jika bucket bersifat publik).
     *
     * @return array{path: string, public_url: ?string}
     */
    public function upload(UploadedFile $file, string $bucket, string $directory = ''): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString().($extension ? ".{$extension}" : '');
        $path = trim($directory, '/') !== '' ? trim($directory, '/')."/{$filename}" : $filename;

        $response = $this->client->asService()
            ->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
            ->withHeaders([
                'x-upsert' => 'true',
            ])
            ->post("/storage/v1/object/{$bucket}/{$path}");

        if ($response->failed()) {
            Log::channel('supabase')->error('Upload ke Supabase Storage gagal.', [
                'bucket' => $bucket,
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengunggah file ke storage.');
        }

        return [
            'path' => $path,
            'public_url' => $this->getPublicUrl($bucket, $path),
        ];
    }

    /**
     * Menghasilkan public URL untuk file di bucket publik.
     */
    public function getPublicUrl(string $bucket, string $path): string
    {
        return rtrim((string) $this->client->getBaseUrl(), '/')."/storage/v1/object/public/{$bucket}/{$path}";
    }

    /**
     * Menghasilkan signed URL sementara untuk file di bucket privat
     * (contoh: dokumen CV pelamar kerja), dengan TTL dalam detik.
     */
    public function getSignedUrl(string $bucket, string $path, int $expiresInSeconds = 3600): string
    {
        $response = $this->client->asService()
            ->post("/storage/v1/object/sign/{$bucket}/{$path}", [
                'expiresIn' => $expiresInSeconds,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Gagal membuat signed URL untuk dokumen.');
        }

        $signedPath = $response->json('signedURL');

        return rtrim((string) $this->client->getBaseUrl(), '/')."/storage/v1{$signedPath}";
    }

    public function delete(string $bucket, string $path): bool
    {
        $response = $this->client->asService()
            ->delete("/storage/v1/object/{$bucket}/{$path}");

        return $response->successful();
    }
}
