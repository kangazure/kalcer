<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Memberi sinyal ke search engine bahwa sitemap telah berubah setiap kali
 * artikel baru dipublikasikan, agar pengindeksan lebih cepat (penting
 * untuk strategi SEO blog).
 */
class PingSearchEngineOnPublish implements ShouldQueue
{
    public string $queue = 'jts_default';

    public function handle(PostPublished $event): void
    {
        if (! app()->environment('production')) {
            return;
        }

        $sitemapUrl = route('sitemap.index');

        $pingTargets = [
            'google' => "https://www.google.com/ping?sitemap={$sitemapUrl}",
            'bing' => "https://www.bing.com/ping?sitemap={$sitemapUrl}",
        ];

        foreach ($pingTargets as $engine => $url) {
            try {
                Http::timeout(5)->get($url);
            } catch (\Throwable $e) {
                Log::info("Ping sitemap ke {$engine} gagal (non-critical).", [
                    'post_id' => $event->post->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
