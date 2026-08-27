<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupController extends Controller
{
    public function index(): View
    {
        return view('admin.popups.index', ['popups' => Popup::query()->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'content' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'link_url' => ['nullable', 'url'],
            'display_rule' => ['required', 'in:once_per_session,every_visit,once_per_day'],
            'is_active' => ['boolean'],
        ]);

        Popup::query()->create($validated);

        return back()->with('success', 'Popup berhasil dibuat.');
    }

    public function update(Request $request, Popup $popup): RedirectResponse
    {
        $popup->update($request->validate([
            'title' => ['required', 'string', 'max:180'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]));

        return back()->with('success', 'Popup berhasil diperbarui.');
    }

    public function destroy(Popup $popup): RedirectResponse
    {
        $popup->delete();

        return back()->with('success', 'Popup berhasil dihapus.');
    }
}
