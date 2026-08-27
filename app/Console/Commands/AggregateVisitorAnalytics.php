<?php

namespace App\Console\Commands;

use App\Models\Analytics;
use App\Models\Visitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateVisitorAnalytics extends Command
{
    protected $signature = 'jts:aggregate-analytics {--date= : Tanggal target (Y-m-d), default kemarin}';

    protected $description = 'Agregasi data visitor harian ke tabel analytics.';

    public function handle(): int
    {
        $date = $this->option('date')
            ? \Carbon\Carbon::parse($this->option('date'))->toDateString()
            : now()->subDay()->toDateString();

        $this->info("Mengagregasi analytics untuk tanggal: {$date}");

        $rows = Visitor::query()
            ->selectRaw('date(visited_at) as metric_date, landing_page as page_path, count(*) as page_views, count(distinct session_id) as unique_visitors')
            ->whereDate('visited_at', $date)
            ->groupBy('metric_date', 'page_path')
            ->get();

        if ($rows->isEmpty()) {
            $this->info("Tidak ada data visitor untuk {$date}.");

            return self::SUCCESS;
        }

        DB::transaction(function () use ($rows, $date) {
            Analytics::query()->where('metric_date', $date)->delete();

            Analytics::query()->insert($rows->map(fn ($row) => [
                'metric_date' => $row->metric_date,
                'page_path' => $row->page_path,
                'page_views' => $row->page_views,
                'unique_visitors' => $row->unique_visitors,
                'created_at' => now(),
            ])->toArray());
        });

        $this->info("Berhasil mengagregasi {$rows->count()} halaman untuk {$date}.");

        return self::SUCCESS;
    }
}
