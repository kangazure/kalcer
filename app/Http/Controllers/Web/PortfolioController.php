<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PortfolioController extends Controller
{
    public function __construct(protected PortfolioRepositoryInterface $portfolio)
    {
    }

    public function index(): View
    {
        return view('pages.portfolio.index', [
            'portfolio' => $this->portfolio->paginate(9),
            'featured' => $this->portfolio->featured(3),
        ]);
    }

    public function show(string $slug): View
    {
        $item = $this->portfolio->findBySlug($slug);

        if (! $item) {
            throw new NotFoundHttpException('Portfolio tidak ditemukan.');
        }

        return view('pages.portfolio.show', ['portfolio' => $item]);
    }
}
