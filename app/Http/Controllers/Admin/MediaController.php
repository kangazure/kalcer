<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(protected MediaService $mediaService)
    {
    }

    public function index(Request $request): View
    {
        return view('admin.media.index', [
            'media' => Media::query()
                ->whereNull('deleted_at')
                ->when($request->type, fn ($q, $t) => $q->where('type', $t))
                ->latest()
                ->paginate(24)
                ->withQueryString(),
        ]);
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'image', 'max:'.config('security.upload.image.max_size_kb')],
        ]);

        $uploaded = [];
        foreach ($request->file('files', []) as $file) {
            $uploaded[] = $this->mediaService->uploadImage($file, 'media', uploaderId: $request->user()->id);
        }

        return response()->json(['success' => true, 'data' => $uploaded]);
    }

    public function destroy(Media $media): RedirectResponse
    {
        $this->mediaService->delete($media);

        return back()->with('success', 'File berhasil dihapus.');
    }
}
