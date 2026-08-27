<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use App\Models\NetworkMonitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoverageAreaController extends Controller
{
    public function index(): View
    {
        return view('admin.coverage-area.index', [
            'areas' => CoverageArea::query()->with('pop:id,node_name')->orderBy('region_name')->get(),
            'nodes' => NetworkMonitor::query()->orderBy('node_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'region_name' => ['required', 'string', 'max:150'],
            'district' => ['nullable', 'string', 'max:100'],
            'center_latitude' => ['required', 'numeric'],
            'center_longitude' => ['required', 'numeric'],
            'radius_meters' => ['nullable', 'integer', 'min:100'],
            'coverage_status' => ['required', 'in:available,partial,planned'],
            'pop_id' => ['nullable', 'integer', 'exists:network_monitor,id'],
        ]);

        CoverageArea::query()->create($validated);

        return redirect()->route('admin.coverage-area.index')->with('success', 'Wilayah berhasil ditambahkan.');
    }

    public function update(Request $request, CoverageArea $coverageArea): RedirectResponse
    {
        $coverageArea->update($request->validate([
            'region_name' => ['required', 'string', 'max:150'],
            'coverage_status' => ['required', 'in:available,partial,planned'],
        ]));

        return back()->with('success', 'Wilayah berhasil diperbarui.');
    }

    public function destroy(CoverageArea $coverageArea): RedirectResponse
    {
        $coverageArea->delete();

        return redirect()->route('admin.coverage-area.index')->with('success', 'Wilayah berhasil dihapus.');
    }
}
