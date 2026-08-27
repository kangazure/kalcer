<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        return view('admin.packages.index', [
            'packages' => Package::query()->with('service:id,name')->orderBy('category')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.packages.create', ['services' => Service::query()->active()->get()]);
    }

    public function store(PackageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        $validated['features'] = array_values(array_filter($validated['features'] ?? []));

        Package::query()->create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dibuat.');
    }

    public function edit(Package $package): View
    {
        return view('admin.packages.edit', ['package' => $package, 'services' => Service::query()->active()->get()]);
    }

    public function update(PackageRequest $request, Package $package): RedirectResponse
    {
        $validated = $request->validated();
        $validated['features'] = array_values(array_filter($validated['features'] ?? []));

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus.');
    }
}
