<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkMonitor;
use App\Repositories\Contracts\NetworkMonitorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NetworkMonitorController extends Controller
{
    public function __construct(protected NetworkMonitorRepositoryInterface $networkMonitor)
    {
    }

    public function index(): View
    {
        return view('admin.network-monitor.index', ['nodes' => NetworkMonitor::query()->orderBy('node_type')->orderBy('node_name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'node_name' => ['required', 'string', 'max:150'],
            'node_type' => ['required', 'in:pop,backbone,core,access_point'],
            'ip_address' => ['nullable', 'ip'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'bandwidth_capacity_mbps' => ['nullable', 'integer', 'min:1'],
        ]);

        NetworkMonitor::query()->create($validated);

        return redirect()->route('admin.network-monitor.index')->with('success', 'Node berhasil ditambahkan.');
    }

    public function show(NetworkMonitor $networkMonitor): View
    {
        return view('admin.network-monitor.show', [
            'node' => $networkMonitor,
            'chartData' => $this->networkMonitor->bandwidthChartData($networkMonitor->id, 24),
        ]);
    }

    public function chartData(NetworkMonitor $networkMonitor, Request $request): JsonResponse
    {
        $hours = (int) $request->query('hours', 24);

        return response()->json(['success' => true, 'data' => $this->networkMonitor->bandwidthChartData($networkMonitor->id, $hours)]);
    }

    public function update(Request $request, NetworkMonitor $networkMonitor): RedirectResponse
    {
        $validated = $request->validate([
            'node_name' => ['required', 'string', 'max:150'],
            'ip_address' => ['nullable', 'ip'],
            'status' => ['required', 'in:online,degraded,offline,maintenance,unknown'],
            'bandwidth_capacity_mbps' => ['nullable', 'integer', 'min:1'],
        ]);

        $networkMonitor->update($validated);

        return back()->with('success', 'Node berhasil diperbarui.');
    }

    public function destroy(NetworkMonitor $networkMonitor): RedirectResponse
    {
        $networkMonitor->delete();

        return redirect()->route('admin.network-monitor.index')->with('success', 'Node berhasil dihapus.');
    }
}
