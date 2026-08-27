<?php

namespace App\Console\Commands;

use App\Models\Career;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'jts:generate-sitemap';

    protected $description = 'Generate sitemap.xml dari seluruh URL publik website.';

    public function handle(): int
    {
        $this->info('Generating sitemap.xml...');

        $sitemap = Sitemap::create();

        $staticUrls = [
            '/' => ['priority' => '1.0', 'changefreq' => Url::CHANGE_FREQUENCY_DAILY],
            '/tentang-kami' => ['priority' => '0.8', 'changefreq' => Url::CHANGE_FREQUENCY_MONTHLY],
            '/layanan' => ['priority' => '0.9', 'changefreq' => Url::CHANGE_FREQUENCY_WEEKLY],
            '/paket-internet' => ['priority' => '0.9', 'changefreq' => Url::CHANGE_FREQUENCY_WEEKLY],
            '/coverage-area' => ['priority' => '0.8', 'changefreq' => Url::CHANGE_FREQUENCY_WEEKLY],
            '/blog' => ['priority' => '0.8', 'changefreq' => Url::CHANGE_FREQUENCY_DAILY],
            '/portfolio' => ['priority' => '0.7', 'changefreq' => Url::CHANGE_FREQUENCY_MONTHLY],
            '/karir' => ['priority' => '0.8', 'changefreq' => Url::CHANGE_FREQUENCY_WEEKLY],
            '/faq' => ['priority' => '0.7', 'changefreq' => Url::CHANGE_FREQUENCY_MONTHLY],
            '/kontak' => ['priority' => '0.8', 'changefreq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticUrls as $path => $config) {
            $sitemap->add(Url::create(config('app.url').$path)->setPriority($config['priority'])->setChangeFrequency($config['changefreq']));
        }

        Service::query()->active()->each(fn (Service $s) => $sitemap->add(
            Url::create(url("/layanan/{$s->slug}"))->setPriority('0.8')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        ));

        Post::query()->where('status', 'published')->each(fn (Post $p) => $sitemap->add(
            Url::create(url("/blog/{$p->slug}"))->setLastModificationDate($p->updated_at)->setPriority('0.7')->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        ));

        Portfolio::query()->where('is_published', true)->each(fn (Portfolio $p) => $sitemap->add(
            Url::create(url("/portfolio/{$p->slug}"))->setLastModificationDate($p->updated_at)->setPriority('0.6')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
        ));

        Career::query()->open()->each(fn (Career $c) => $sitemap->add(
            Url::create(url("/karir/{$c->slug}"))->setPriority('0.7')->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        ));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml berhasil di-generate di public/sitemap.xml');

        return self::SUCCESS;
    }
}
