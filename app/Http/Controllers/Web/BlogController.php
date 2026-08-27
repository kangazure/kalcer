<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CommentRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Public\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function __construct(protected BlogService $blogService)
    {
    }

    public function index(Request $request): View
    {
        $posts = $this->blogService->listPublished(
            filters: $request->only(['category_slug', 'tag_slug']),
            keyword: $request->query('search'),
            perPage: 9,
        );

        return view('pages.blog.index', [
            'posts' => $posts,
            'categories' => Category::query()->active()->withCount('posts')->orderBy('sort_order')->get(),
            'popularTags' => Tag::query()->orderByDesc('usage_count')->limit(15)->get(),
            'popularPosts' => $this->blogService->recentForHome(5),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->blogService->showBySlug($slug);

        if (! $post) {
            throw new NotFoundHttpException('Artikel tidak ditemukan.');
        }

        return view('pages.blog.show', array_merge(
            ['post' => $post],
            $this->blogService->sidebarData($post->id),
        ));
    }

    public function toggleLike(int $postId, Request $request): JsonResponse
    {
        $liked = $this->blogService->toggleLike($postId, $request->user()?->id, $request->ip());

        return response()->json(['success' => true, 'liked' => $liked]);
    }

    public function toggleBookmark(int $postId, Request $request): JsonResponse
    {
        $bookmarked = $this->blogService->toggleBookmark($postId, $request->user()->id);

        return response()->json(['success' => true, 'bookmarked' => $bookmarked]);
    }

    public function storeComment(int $postId, CommentRequest $request): JsonResponse
    {
        $comment = $this->blogService->submitComment($postId, $request->validated(), $request->user()?->id);

        return response()->json([
            'success' => true,
            'message' => 'Komentar Anda telah dikirim dan menunggu moderasi.',
            'data' => $comment,
        ], 201);
    }

    public function rssFeed(): Response
    {
        $posts = Post::query()
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        $xml = view('blog.rss', ['posts' => $posts])->render();

        return response($xml, 200)->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
