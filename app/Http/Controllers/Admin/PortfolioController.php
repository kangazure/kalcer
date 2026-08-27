<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('admin.portfolio.index', ['portfolio' => Portfolio::query()->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.portfolio.create');
    }

    public function store(PortfolioRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(4);
        $validated['created_by'] = $request->user()->id;

        Portfolio::query()->create($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio berhasil dibuat.');
    }

    public function edit(Portfolio $portfolio): View
    {
        return view('admin.portfolio.edit', ['portfolio' => $portfolio]);
    }

    public function update(PortfolioRequest $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio->update($request->validated());

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio berhasil dihapus.');
    }
}
