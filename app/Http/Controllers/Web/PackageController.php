<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PackageRepositoryInterface;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function __construct(protected PackageRepositoryInterface $packages)
    {
    }

    public function index(): View
    {
        return view('pages.packages.index', [
            'packagesByCategory' => $this->packages->allActiveGroupedByCategory(),
        ]);
    }
}
