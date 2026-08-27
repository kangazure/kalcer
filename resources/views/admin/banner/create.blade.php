@extends('layouts.admin')
@section('page_title', isset($banner) ? 'Edit Banner' : 'Banner Baru')
@section('breadcrumb')
    <a href="{{ route('admin.banners.index') }}" class="text-ink-400 hover:text-brand">Banner & Slider</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($banner) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($banner) ? route('admin.banners.update', $banner->id) : route('admin.banners.store') }}" class="max-w-2xl">
    @csrf
    @if(isset($banner)) @method('PUT') @endif
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
        <div><label class="form-label">Judul</label><input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required class="form-input text-sm"></div>
        <div>
            <label class="form-label">Posisi</label>
            <select name="position" class="form-select text-sm">
                @foreach(['home_hero'=>'Home Hero','sidebar'=>'Sidebar','popup'=>'Popup','top_bar'=>'Top Bar','footer'=>'Footer'] as $val=>$label)
                    <option value="{{ $val }}" {{ old('position', $banner->position ?? '')===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Image URL (Desktop)</label><input type="url" name="image_url" value="{{ old('image_url', $banner->image_url ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Image URL (Mobile, opsional)</label><input type="url" name="image_url_mobile" value="{{ old('image_url_mobile', $banner->image_url_mobile ?? '') }}" class="form-input text-sm"></div>
        <div><label class="form-label">Link URL</label><input type="url" name="link_url" value="{{ old('link_url', $banner->link_url ?? '') }}" class="form-input text-sm"></div>
        <div><label class="form-label">Alt Text</label><input type="text" name="alt_text" value="{{ old('alt_text', $banner->alt_text ?? '') }}" class="form-input text-sm"></div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div><label class="form-label text-xs">Mulai Tampil</label><input type="datetime-local" name="starts_at" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d\TH:i') ?? '') }}" class="form-input text-sm"></div>
            <div><label class="form-label text-xs">Berakhir</label><input type="datetime-local" name="ends_at" value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d\TH:i') ?? '') }}" class="form-input text-sm"></div>
        </div>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand"><span class="text-sm text-ink-700 dark:text-ink-300">Aktif</span></label>
    </div>
    <div class="flex gap-2 mt-5">
        <button type="submit" class="btn-primary btn-sm">Simpan</button>
        <a href="{{ route('admin.banners.index') }}" class="btn-ghost btn-sm">Batal</a>
    </div>
</form>
@endsection
