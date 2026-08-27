<?php

namespace App\Providers;

use App\Repositories\Contracts\CareerRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Repositories\Contracts\CoverageAreaRepositoryInterface;
use App\Repositories\Contracts\NetworkMonitorRepositoryInterface;
use App\Repositories\Contracts\PackageRepositoryInterface;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\TestimonialRepositoryInterface;
use App\Repositories\Eloquent\CareerRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ContactRepository;
use App\Repositories\Eloquent\CoverageAreaRepository;
use App\Repositories\Eloquent\NetworkMonitorRepository;
use App\Repositories\Eloquent\PackageRepository;
use App\Repositories\Eloquent\PortfolioRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\TestimonialRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Mengikat seluruh kontrak repository ke implementasi Eloquent-nya.
 * Memudahkan pergantian sumber data (mis. ke Supabase REST langsung)
 * di masa depan tanpa mengubah kode Service/Controller pemanggil.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        PostRepositoryInterface::class => PostRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        PackageRepositoryInterface::class => PackageRepository::class,
        PortfolioRepositoryInterface::class => PortfolioRepository::class,
        TestimonialRepositoryInterface::class => TestimonialRepository::class,
        CareerRepositoryInterface::class => CareerRepository::class,
        ContactRepositoryInterface::class => ContactRepository::class,
        CoverageAreaRepositoryInterface::class => CoverageAreaRepository::class,
        NetworkMonitorRepositoryInterface::class => NetworkMonitorRepository::class,
    ];

    public function register(): void
    {
        //
    }
}
