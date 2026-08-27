@extends('layouts.admin')
@section('page_title', isset($slider) ? 'Edit Slide' : 'Slide Baru')
@section('breadcrumb')
    <a href="{{ route('admin.banners.index') }}" class="text-ink-400 hover:text-brand">Banner & Slider</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($slider) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($slider) ? route('admin.sliders.update', $slider->id) : route('admin.sliders.store') }}" class="max-w-2xl">
    @csrf
    @if(isset($slider)) @method('PUT') @endif
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
        <div><label class="form-label">Judul</label><input type="text" name="title" value="{{ old('title', $slider->title ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Subjudul</label><input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle ?? '') }}" class="form-input text-sm"></div>
        <div><label class="form-label">Deskripsi</label><textarea name="description" rows="2" class="form-textarea text-sm">{{ old('description', $slider->description ?? '') }}</textarea></div>
        <div><label class="form-label">Image URL</label><input type="url" name="image_url" value="{{ old('image_url', $slider->image_url ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Video URL (opsional)</label><input type="url" name="video_url" value="{{ old('video_url', $slider->video_url ?? '') }}" class="form-input text-sm"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="form-label">Label Tombol CTA</label><input type="text" name="cta_label" value="{{ old('cta_label', $slider->cta_label ?? '') }}" class="form-input text-sm"></div>
            <div><label class="form-label">Link CTA</label><input type="url" name="cta_url" value="{{ old('cta_url', $slider->cta_url ?? '') }}" class="form-input text-sm"></div>
        </div>
        <div><label class="form-label text-xs">Urutan Tampil</label><input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}" class="form-input text-sm"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand"><span class="text-sm text-ink-700 dark:text-ink-300">Aktif</span></label>
    </div>
    <div class="flex gap-2 mt-5">
        <button type="submit" class="btn-primary btn-sm">Simpan</button>
        <a href="{{ route('admin.banners.index') }}" class="btn-ghost btn-sm">Batal</a>
    </div>
</form>
@endsection
