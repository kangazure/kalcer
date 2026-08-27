<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('pages.gallery.index', [
            'galleries' => Gallery::query()->where('is_published', true)->orderBy('sort_order')->paginate(12),
        ]);
    }

    public function show(string $slug): View
    {
        $gallery = Gallery::query()->where('is_published', true)->where('slug', $slug)->first();

        if (! $gallery) {
            throw new NotFoundHttpException('Album galeri tidak ditemukan.');
        }

        return view('pages.gallery.show', [
            'gallery' => $gallery,
            'photos' => $gallery->media()->orderBy('sort_order')->get(),
        ]);
    }
}
