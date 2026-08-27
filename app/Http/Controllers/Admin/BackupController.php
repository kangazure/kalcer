<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BackupController extends Controller
{
    public function index(): View
    {
        $disk = Storage::disk(config('backup.backup.destination.disks', ['s3'])[0] ?? 'local');

        $backups = collect();
        try {
            foreach ($disk->allFiles('ptjts-backup') as $path) {
                $backups->push([
                    'name' => basename($path),
                    'path' => $path,
                    'size' => $this->formatBytes($disk->size($path)),
                    'date' => date('d M Y H:i', $disk->lastModified($path)),
                ]);
            }
        } catch (\Throwable $e) {
            // Disk backup belum dikonfigurasi / belum ada file — tampilkan kosong saja.
        }

        return view('admin.backup.index', ['backups' => $backups->sortByDesc('date')->values()]);
    }

    public function run(): RedirectResponse
    {
        Artisan::call('backup:run', ['--only-db' => true]);

        return back()->with('success', 'Proses backup database telah dijalankan.');
    }

    public function download(string $path)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks', ['s3'])[0] ?? 'local');

        return $disk->download($path);
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
