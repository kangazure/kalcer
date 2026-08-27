<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PortfolioResource;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends ApiController
{
    public function __construct(protected PortfolioRepositoryInterface $portfolio)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $items = $this->portfolio->search(
            filters: $request->only(['category']),
            keyword: $request->query('search'),
            sort: $request->query('sort'),
            perPage: (int) $request->query('per_page', 12),
        );

        return $this->paginated($items, PortfolioResource::class);
    }

    public function show(string $slug): JsonResponse
    {
        $item = $this->portfolio->findBySlug($slug);

        if (! $item) {
            return $this->notFound('Portfolio tidak ditemukan.');
        }

        return $this->success(new PortfolioResource($item));
    }

    public function featured(): JsonResponse
    {
        return $this->success(PortfolioResource::collection($this->portfolio->featured(6)));
    }
}
