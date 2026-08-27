@extends('layouts.admin')
@section('page_title', isset($service) ? 'Edit Layanan' : 'Layanan Baru')
@section('breadcrumb')
    <a href="{{ route('admin.services.index') }}" class="text-ink-400 hover:text-brand">Layanan</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($service) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST"
      action="{{ isset($service) ? route('admin.services.update', $service->id) : route('admin.services.store') }}"
      x-data="{
          features: {{ json_encode(old('features', $service->features ?? [''])) }},
          benefits: {{ json_encode(old('benefits', $service->benefits ?? [''])) }}
      }">
    @csrf
    @if(isset($service)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
                <div><label class="form-label">Nama Layanan</label><input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required class="form-input text-sm"></div>
                <div><label class="form-label">Ikon (nama identifier, contoh: home-wifi)</label><input type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}" class="form-input text-sm"></div>
                <div><label class="form-label">Deskripsi Singkat</label><textarea name="short_description" rows="2" maxlength="300" class="form-textarea text-sm">{{ old('short_description', $service->short_description ?? '') }}</textarea></div>
                <div><label class="form-label">Deskripsi Lengkap (Markdown)</label><textarea name="description" rows="10" class="form-textarea text-sm font-mono">{{ old('description', $service->description ?? '') }}</textarea></div>
                <div><label class="form-label">Cover Image URL</label><input type="url" name="cover_image_url" value="{{ old('cover_image_url', $service->cover_image_url ?? '') }}" class="form-input text-sm"></div>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="form-label mb-0">Fitur Utama</label>
                    <button type="button" @click="features.push('')" class="text-xs text-brand hover:underline">+ Tambah</button>
                </div>
                <template x-for="(f, i) in features" :key="i">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="`features[${i}]`" x-model="features[i]" class="form-input text-sm">
                        <button type="button" @click="features.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">✕</button>
                    </div>
                </template>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="form-label mb-0">Manfaat</label>
                    <button type="button" @click="benefits.push('')" class="text-xs text-brand hover:underline">+ Tambah</button>
                </div>
                <template x-for="(b, i) in benefits" :key="i">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="`benefits[${i}]`" x-model="benefits[i]" class="form-input text-sm">
                        <button type="button" @click="benefits.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">✕</button>
                    </div>
                </template>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 sticky top-20">
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="is-active" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-active" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Aktif</label>
                </div>
                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" id="is-featured-home" name="is_featured_home" value="1" {{ old('is_featured_home', $service->is_featured_home ?? false) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-featured-home" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Tampilkan di homepage</label>
                </div>
                <div class="mb-4"><label class="form-label text-xs">Urutan Tampil</label><input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" class="form-input text-sm"></div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <a href="{{ route('admin.services.index') }}" class="btn-ghost btn-sm">Batal</a>
                </div>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6">
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">SEO</h3>
                <div class="space-y-3">
                    <div><label class="form-label text-xs">SEO Title</label><input type="text" name="seo_title" value="{{ old('seo_title', $service->seo_title ?? '') }}" class="form-input text-sm"></div>
                    <div><label class="form-label text-xs">SEO Description</label><textarea name="seo_description" rows="2" class="form-textarea text-sm">{{ old('seo_description', $service->seo_description ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
