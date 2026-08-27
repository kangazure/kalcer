<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        return view('admin.tags.index', [
            'tags' => Tag::query()->orderByDesc('usage_count')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:60']]);
        $validated['slug'] = Str::slug($validated['name']);

        Tag::query()->create($validated);

        return back()->with('success', 'Tag berhasil dibuat.');
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:60']]);

        $tag->update($validated);

        return back()->with('success', 'Tag berhasil diperbarui.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }
}
