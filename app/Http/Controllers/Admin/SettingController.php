<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(protected SettingService $settingService)
    {
    }

    public function index(?string $group = 'general'): View
    {
        $settingsRaw = \App\Models\Setting::query()->where('group_name', $group)->get();

        $settings = $settingsRaw->mapWithKeys(fn ($s) => [
            $s->key => ['value' => is_string($s->value) ? json_decode($s->value, true) : $s->value, 'label' => $s->label, 'description' => $s->description],
        ]);

        return view('admin.settings.index', ['group' => $group, 'settings' => $settings]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $values = $request->input('settings', []);

        $this->settingService->setMany($group, $values, $request->user()->id);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
