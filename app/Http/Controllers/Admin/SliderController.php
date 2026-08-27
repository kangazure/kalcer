<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(SliderRequest $request): RedirectResponse
    {
        Slider::query()->create($request->validated());

        return redirect()->route('admin.banners.index')->with('success', 'Slide berhasil dibuat.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', ['slider' => $slider]);
    }

    public function update(SliderRequest $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validated());

        return redirect()->route('admin.banners.index')->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $slider->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Slide berhasil dihapus.');
    }

    public function reorder(Request $request): JsonResponse
    {
        foreach ($request->input('order', []) as $index => $id) {
            Slider::query()->where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
