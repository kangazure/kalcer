@extends('layouts.admin')
@section('page_title', isset($post) ? 'Edit Artikel' : 'Tulis Artikel Baru')
@section('breadcrumb')
    <a href="{{ route('admin.posts.index') }}" class="text-ink-400 hover:text-brand">Artikel</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($post) ? 'Edit' : 'Tulis Baru' }}</span>
@endsection

@section('content')
<form method="POST"
      action="{{ isset($post) ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}"
      x-data="{ status: '{{ old('status', $post->status ?? 'draft') }}', saving: false, wordCount: 0,
          init() { const c = document.getElementById('post-content'); if (c) { this.wordCount = c.value.trim().split(/\s+/).filter(Boolean).length; c.addEventListener('input', () => this.wordCount = c.value.trim().split(/\s+/).filter(Boolean).length); } } }"
      @submit="saving = true">
    @csrf
    @if(isset($post)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div>
                <input name="title" type="text" value="{{ old('title', $post->title ?? '') }}" placeholder="Judul artikel yang menarik..." required
                       class="w-full px-5 py-4 text-xl font-bold bg-white dark:bg-ink-900 border border-ink-200 dark:border-ink-700 text-ink-900 dark:text-white placeholder-ink-300 rounded-2xl focus:outline-none focus:border-brand transition-colors">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Ringkasan (SEO meta description)</label>
                <textarea name="excerpt" rows="2" maxlength="500" class="form-textarea">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-ink-100 dark:border-ink-800 bg-ink-50 dark:bg-ink-950">
                    <span class="text-xs font-medium text-ink-500 uppercase tracking-wide">Konten Artikel (Markdown)</span>
                    <span class="text-xs text-ink-400" x-text="wordCount + ' kata'"></span>
                </div>
                <textarea id="post-content" name="content" rows="24" required
                          placeholder="# Judul Bagian&#10;&#10;Tulis konten artikel Anda dalam format Markdown..."
                          class="w-full px-5 py-4 bg-transparent text-ink-800 dark:text-ink-200 text-sm font-mono leading-relaxed resize-none focus:outline-none">{{ old('content', $post->content ?? '') }}</textarea>
                @error('content') <p class="form-error px-4 pb-3">{{ $message }}</p> @enderror
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-5">
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">Cover Image</h3>
                <div x-data="{ url: '{{ old('cover_image_url', $post->cover_image_url ?? '') }}' }">
                    <div x-show="url" class="mb-3"><img :src="url" alt="Cover preview" class="w-full h-40 object-cover rounded-xl"></div>
                    <input type="text" name="cover_image_url" x-model="url" placeholder="URL gambar cover (dari media manager)..." class="form-input text-sm">
                    <p class="text-xs text-ink-400 mt-1.5">Gunakan <a href="{{ route('admin.media.index') }}" class="text-brand hover:underline" target="_blank">Media Manager</a> untuk upload.</p>
                </div>
            </div>

            <div x-data="{ open: false }" class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-5">
                <button type="button" @click="open = !open" class="flex items-center justify-between w-full">
                    <h3 class="font-semibold text-ink-900 dark:text-white text-sm">SEO & Open Graph</h3>
                    <svg class="w-4 h-4 text-ink-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-collapse class="mt-4 space-y-3">
                    <div><label class="form-label">SEO Title</label><input type="text" name="seo_title" value="{{ old('seo_title', $post->seo_title ?? '') }}" maxlength="160" class="form-input text-sm"></div>
                    <div><label class="form-label">SEO Description</label><textarea name="seo_description" rows="2" maxlength="320" class="form-textarea text-sm">{{ old('seo_description', $post->seo_description ?? '') }}</textarea></div>
                    <div><label class="form-label">OG Image URL</label><input type="url" name="og_image_url" value="{{ old('og_image_url', $post->og_image_url ?? '') }}" class="form-input text-sm"></div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-5 sticky top-20">
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Publikasi</h3>
                <div class="space-y-3 mb-5">
                    <div>
                        <label class="form-label text-xs">Status</label>
                        <select name="status" x-model="status" class="form-select text-sm">
                            <option value="draft">Draft</option>
                            <option value="review">Review</option>
                            @can('publish', $post ?? new \App\Models\Post)
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            @endcan
                        </select>
                    </div>
                    <div><label class="form-label text-xs">Tanggal Publikasi</label><input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i') ?? '') }}" class="form-input text-sm"></div>
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="is-featured" name="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-featured" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Featured post</label>
                </div>
                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" id="is-pinned" name="is_pinned" value="1" {{ old('is_pinned', $post->is_pinned ?? false) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-pinned" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Pin di atas listing</label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" :disabled="saving" class="flex-1 btn-primary btn-sm text-sm justify-center">
                        <span x-text="saving ? 'Menyimpan...' : (status === 'published' ? 'Publikasikan' : 'Simpan')"></span>
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn-ghost btn-sm">Batal</a>
                </div>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-5">
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">Kategori</h3>
                <select name="category_id" class="form-select text-sm" required>
                    <option value="">Pilih kategori...</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="form-error mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-5">
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">Tag</h3>
                <div class="flex flex-wrap gap-2" role="group" aria-label="Pilih tag artikel">
                    @php $selected = old('tags', $post->tags?->pluck('id')->toArray() ?? []); @endphp
                    @foreach($tags ?? [] as $tag)
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, $selected) ? 'checked' : '' }} class="rounded border-ink-300 text-brand text-xs">
                            <span class="tag-pill text-xs cursor-pointer">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
