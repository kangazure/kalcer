<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Public\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(protected BlogService $blogService)
    {
    }

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with(['category:id,name', 'author:id,name'])
            ->when($request->search, fn ($q, $s) => $q->where('title', 'ilike', "%{$s}%"))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->category_id, fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => Category::query()->active()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::query()->active()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['author_id'] = $request->user()->id;
        $validated['slug'] = $this->blogService->generateUniqueSlug($validated['title']);

        $post = Post::query()->create($validated);
        $post->tags()->sync($validated['tags'] ?? []);

        if ($post->status === 'published') {
            $this->blogService->handlePublished($post);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post->load('tags'),
            'categories' => Category::query()->active()->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();
        $wasPublished = $post->status === 'published';

        $post->update($validated);
        $post->tags()->sync($validated['tags'] ?? []);

        if (! $wasPublished && $post->status === 'published') {
            $this->blogService->handlePublished($post);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
