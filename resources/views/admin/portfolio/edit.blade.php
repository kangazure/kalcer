@extends('layouts.admin')
@section('page_title', isset($portfolio) ? 'Edit Portfolio' : 'Portfolio Baru')
@section('breadcrumb')
    <a href="{{ route('admin.portfolio.index') }}" class="text-ink-400 hover:text-brand">Portfolio</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($portfolio) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($portfolio) ? route('admin.portfolio.update', $portfolio->id) : route('admin.portfolio.store') }}">
    @csrf
    @if(isset($portfolio)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
                <div><label class="form-label">Judul Proyek</label><input type="text" name="title" value="{{ old('title', $portfolio->title ?? '') }}" required class="form-input text-sm"></div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div><label class="form-label">Nama Klien</label><input type="text" name="client_name" value="{{ old('client_name', $portfolio->client_name ?? '') }}" class="form-input text-sm"></div>
                    <div><label class="form-label">Kategori</label><input type="text" name="category" value="{{ old('category', $portfolio->category ?? '') }}" placeholder="Internet Rumah, Dedicated, dsb" class="form-input text-sm"></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div><label class="form-label">Lokasi</label><input type="text" name="location" value="{{ old('location', $portfolio->location ?? '') }}" class="form-input text-sm"></div>
                    <div><label class="form-label">Tahun Proyek</label><input type="number" name="project_year" value="{{ old('project_year', $portfolio->project_year ?? date('Y')) }}" class="form-input text-sm"></div>
                </div>
                <div><label class="form-label">Ringkasan</label><textarea name="summary" rows="2" maxlength="500" class="form-textarea text-sm">{{ old('summary', $portfolio->summary ?? '') }}</textarea></div>
                <div><label class="form-label">Deskripsi Lengkap (Markdown)</label><textarea name="description" rows="10" class="form-textarea text-sm font-mono">{{ old('description', $portfolio->description ?? '') }}</textarea></div>
                <div><label class="form-label">Cover Image URL</label><input type="url" name="cover_image_url" value="{{ old('cover_image_url', $portfolio->cover_image_url ?? '') }}" class="form-input text-sm"></div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 sticky top-20">
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div><label class="form-label text-xs">Label Hasil</label><input type="text" name="result_metric_label" value="{{ old('result_metric_label', $portfolio->result_metric_label ?? '') }}" placeholder="Uptime" class="form-input text-sm"></div>
                    <div><label class="form-label text-xs">Nilai Hasil</label><input type="text" name="result_metric_value" value="{{ old('result_metric_value', $portfolio->result_metric_value ?? '') }}" placeholder="99.9%" class="form-input text-sm"></div>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="is-featured" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured ?? false) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-featured" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Featured</label>
                </div>
                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" id="is-published" name="is_published" value="1" {{ old('is_published', $portfolio->is_published ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-published" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Publikasikan</label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <a href="{{ route('admin.portfolio.index') }}" class="btn-ghost btn-sm">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
