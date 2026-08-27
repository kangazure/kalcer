<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PostResource;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Public\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function __construct(
        protected PostRepositoryInterface $posts,
        protected BlogService $blogService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $posts = $this->blogService->listPublished(
            filters: $request->only(['category_slug', 'tag_slug']),
            keyword: $request->query('search'),
            perPage: (int) $request->query('per_page', 10),
        );

        return $this->paginated($posts, PostResource::class);
    }

    public function show(string $slug): JsonResponse
    {
        $post = $this->blogService->showBySlug($slug);

        if (! $post) {
            return $this->notFound('Artikel tidak ditemukan.');
        }

        return $this->success(new PostResource($post->load(['category', 'author', 'tags'])));
    }

    public function featured(): JsonResponse
    {
        return $this->success(PostResource::collection($this->blogService->featuredForHome(5)));
    }

    public function trending(): JsonResponse
    {
        return $this->success(PostResource::collection($this->posts->trending(10)));
    }

    public function related(int $postId): JsonResponse
    {
        return $this->success(PostResource::collection($this->posts->related($postId, 4)));
    }
}
