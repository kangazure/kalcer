<?php

namespace App\Console\Commands;

use App\Models\Visitor;
use Illuminate\Console\Command;

class CleanOldVisitorLogs extends Command
{
    protected $signature = 'jts:clean-visitor-logs {--days=90 : Umur data dalam hari sebelum dihapus}';

    protected $description = 'Hapus data raw visitor yang lebih dari N hari (sudah diagregasi).';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $count = Visitor::query()->where('visited_at', '<', $cutoff)->count();

        if ($count === 0) {
            $this->info("Tidak ada data visitor yang lebih dari {$days} hari.");

            return self::SUCCESS;
        }

        if (! $this->confirm("Akan menghapus {$count} baris visitor sebelum {$cutoff->toDateString()}. Lanjutkan?", true)) {
            $this->info('Dibatalkan.');

            return self::SUCCESS;
        }

        $deleted = Visitor::query()->where('visited_at', '<', $cutoff)->delete();

        $this->info("Berhasil menghapus {$deleted} baris visitor log.");

        return self::SUCCESS;
    }
}
