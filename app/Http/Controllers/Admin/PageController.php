<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mengelola halaman statis (Privacy Policy, Terms) yang disimpan sebagai
 * setting group 'pages' agar dapat diedit tanpa perlu migration tambahan.
 */
class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Setting::query()->where('group_name', 'pages')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100'],
            'label' => ['required', 'string', 'max:150'],
            'value' => ['required', 'string'],
        ]);

        Setting::query()->create([
            'group_name' => 'pages',
            'key' => $validated['key'],
            'label' => $validated['label'],
            'value' => json_encode($validated['value']),
            'is_public' => true,
        ]);

        return back()->with('success', 'Halaman berhasil dibuat.');
    }

    public function update(Request $request, Setting $page): RedirectResponse
    {
        $validated = $request->validate(['value' => ['required', 'string']]);

        $page->update(['value' => json_encode($validated['value'])]);

        return back()->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Setting $page): RedirectResponse
    {
        $page->delete();

        return back()->with('success', 'Halaman berhasil dihapus.');
    }
}
