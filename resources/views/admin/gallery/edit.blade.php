@extends('layouts.admin')
@section('page_title', isset($gallery) ? 'Edit Album' : 'Album Baru')
@section('breadcrumb')
    <a href="{{ route('admin.gallery.index') }}" class="text-ink-400 hover:text-brand">Galeri</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($gallery) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($gallery) ? route('admin.gallery.update', $gallery->id) : route('admin.gallery.store') }}" class="max-w-2xl">
    @csrf
    @if(isset($gallery)) @method('PUT') @endif
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
        <div><label class="form-label">Judul Album</label><input type="text" name="title" value="{{ old('title', $gallery->title ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Kategori</label><input type="text" name="category" value="{{ old('category', $gallery->category ?? '') }}" placeholder="Instalasi, Event, Kegiatan Internal" class="form-input text-sm"></div>
        <div><label class="form-label">Deskripsi</label><textarea name="description" rows="3" class="form-textarea text-sm">{{ old('description', $gallery->description ?? '') }}</textarea></div>
        <div><label class="form-label">Cover Image URL</label><input type="url" name="cover_image_url" value="{{ old('cover_image_url', $gallery->cover_image_url ?? '') }}" class="form-input text-sm"></div>
        <div><label class="form-label text-xs">Urutan Tampil</label><input type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order ?? 0) }}" class="form-input text-sm"></div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $gallery->is_published ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand"><span class="text-sm text-ink-700 dark:text-ink-300">Publikasikan</span></label>
    </div>
    <div class="flex gap-2 mt-5">
        <button type="submit" class="btn-primary btn-sm">Simpan</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn-ghost btn-sm">Batal</a>
    </div>
</form>
@endsection
