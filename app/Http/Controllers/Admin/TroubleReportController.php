<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TroubleReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TroubleReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.trouble-report.index', [
            'reports' => TroubleReport::query()
                ->with(['node:id,node_name', 'assignee:id,name'])
                ->when($request->status, fn ($q, $s) => $q->where('status', $s))
                ->when($request->severity, fn ($q, $s) => $q->where('severity', $s))
                ->latest('reported_at')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function show(TroubleReport $troubleReport): View
    {
        return view('admin.trouble-report.show', [
            'report' => $troubleReport->load(['node', 'assignee']),
            'operators' => User::query()->whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'admin', 'operator']))->get(),
        ]);
    }

    public function update(Request $request, TroubleReport $troubleReport): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,investigating,resolved,closed'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'resolution_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['status'] === 'resolved' && $troubleReport->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $troubleReport->update($validated);

        return redirect()->route('admin.trouble-report.show', $troubleReport)->with('success', 'Laporan gangguan berhasil diperbarui.');
    }
}
