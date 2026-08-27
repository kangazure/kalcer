<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', ['galleries' => Gallery::query()->orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(GalleryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(4);
        $validated['created_by'] = $request->user()->id;

        Gallery::query()->create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Album berhasil dibuat.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.gallery.edit', ['gallery' => $gallery]);
    }

    public function update(GalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $gallery->update($request->validated());

        return redirect()->route('admin.gallery.index')->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Album berhasil dihapus.');
    }
}
