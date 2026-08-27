<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CareerRequest;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('admin.career.index', ['careers' => Career::query()->withCount('applications')->latest()->get()]);
    }

    public function store(CareerRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(4);
        $validated['created_by'] = $request->user()->id;

        Career::query()->create($validated);

        return redirect()->route('admin.career.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function update(CareerRequest $request, Career $career): RedirectResponse
    {
        $career->update($request->validated());

        return redirect()->route('admin.career.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Career $career): RedirectResponse
    {
        $career->delete();

        return redirect()->route('admin.career.index')->with('success', 'Lowongan berhasil dihapus.');
    }

    public function applications(Career $career): View
    {
        return view('admin.career.applications', [
            'career' => $career,
            'applications' => $career->applications()->with('resume')->latest()->get(),
        ]);
    }

    public function updateApplicationStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', 'in:submitted,screening,interview,offered,hired,rejected']]);

        $application->update($validated);

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
