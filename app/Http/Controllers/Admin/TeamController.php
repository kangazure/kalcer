<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('admin.team.index', ['team' => Team::query()->orderBy('sort_order')->get()]);
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        Team::query()->create($request->validated());

        return back()->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $team->update($request->validated());

        return back()->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }
}
