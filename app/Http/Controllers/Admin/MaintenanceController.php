<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(): View
    {
        return view('admin.maintenance.index', ['maintenances' => Maintenance::query()->latest('scheduled_start')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'scheduled_start' => ['required', 'date'],
            'scheduled_end' => ['required', 'date', 'after:scheduled_start'],
        ]);

        $validated['created_by'] = $request->user()->id;

        Maintenance::query()->create($validated);

        return redirect()->route('admin.maintenance.index')->with('success', 'Jadwal maintenance berhasil dibuat.');
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', 'in:scheduled,ongoing,completed,cancelled']]);

        if ($validated['status'] === 'ongoing' && ! $maintenance->actual_start) {
            $validated['actual_start'] = now();
        }
        if ($validated['status'] === 'completed' && ! $maintenance->actual_end) {
            $validated['actual_end'] = now();
        }

        $maintenance->update($validated);

        return back()->with('success', 'Status maintenance berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $maintenance->delete();

        return redirect()->route('admin.maintenance.index')->with('success', 'Jadwal maintenance berhasil dihapus.');
    }
}
