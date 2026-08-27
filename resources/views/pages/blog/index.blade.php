@extends('layouts.app')
@push('seo_title')Blog — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Artikel seputar teknologi internet, tips jaringan, dan berita dari PT Jaringan Teknologi Sejahtera (JTS).@endpush

@section('content')
<section class="page-hero py-16 lg:py-20">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Blog</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Wawasan &amp; Tips Seputar Internet</h1>
        <form method="GET" action="{{ route('blog.index') }}" class="max-w-lg mx-auto mt-8" data-aos="fade-up" data-aos-delay="100">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." class="w-full pl-12 pr-4 py-3.5 bg-white/10 border border-white/15 text-white placeholder-ink-500 rounded-2xl focus:outline-none focus:border-brand transition-colors">
            </div>
        </form>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-8">
                @forelse($posts as $index => $post)
                    <article class="group flex flex-col sm:flex-row gap-5 pb-8 border-b border-ink-100 dark:border-ink-800 last:border-0" data-aos="fade-up" data-aos-delay="{{ min($index * 40, 200) }}">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block w-full sm:w-48 h-40 sm:h-32 shrink-0 rounded-xl overflow-hidden">
                            @if($post->cover_image_url)
                                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand/20 to-ink-200 dark:to-ink-700 flex items-center justify-center" aria-hidden="true">
                                    <svg class="w-8 h-8 text-brand/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </a>
                        <div class="flex-1 min-w-0">
                            @if($post->category)
                                <a href="{{ route('blog.index', ['category_slug' => $post->category->slug]) }}" class="inline-block text-xs font-semibold text-brand uppercase tracking-wider mb-2 hover:underline">{{ $post->category->name }}</a>
                            @endif
                            <h2 class="font-bold text-lg text-ink-900 dark:text-white mb-2 group-hover:text-brand transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-sm text-ink-500 dark:text-ink-400 line-clamp-2 mb-3">{{ $post->excerpt }}</p>
                            <div class="flex items-center gap-3 text-xs text-ink-400">
                                <span>{{ $post->author?->name ?? 'JTS' }}</span>
                                <span>·</span>
                                <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('d M Y') }}</time>
                                <span>·</span>
                                <span>{{ $post->reading_time_minutes }} menit baca</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-16 text-ink-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-ink-200 dark:text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p>Belum ada artikel ditemukan.</p>
                    </div>
                @endforelse

                @if($posts->hasPages())
                    <div class="flex items-center justify-center gap-1 pt-6">
                        <a href="{{ $posts->previousPageUrl() ?? '#' }}" class="pagination-link {{ !$posts->previousPageUrl() ? 'disabled' : '' }}" aria-label="Halaman sebelumnya">←</a>
                        @foreach($posts->getUrlRange(max(1, $posts->currentPage()-2), min($posts->lastPage(), $posts->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}" class="pagination-link {{ $page == $posts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                        <a href="{{ $posts->nextPageUrl() ?? '#' }}" class="pagination-link {{ !$posts->nextPageUrl() ? 'disabled' : '' }}" aria-label="Halaman berikutnya">→</a>
                    </div>
                @endif
            </div>

            <aside class="space-y-8">
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6">
                    <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blog.index', ['category_slug' => $cat->slug]) }}" class="flex items-center justify-between text-sm text-ink-600 dark:text-ink-400 hover:text-brand transition-colors">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-xs text-ink-400">{{ $cat->posts_count ?? 0 }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($popularTags->isNotEmpty())
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6">
                    <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Tag Populer</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('blog.index', ['tag_slug' => $tag->slug]) }}" class="tag-pill">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($popularPosts->isNotEmpty())
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6">
                    <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Artikel Terpopuler</h3>
                    <div class="space-y-4">
                        @foreach($popularPosts as $index => $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 group">
                                <span class="text-2xl font-bold text-ink-200 dark:text-ink-700 shrink-0">{{ $index + 1 }}</span>
                                <span class="text-sm text-ink-700 dark:text-ink-300 group-hover:text-brand transition-colors line-clamp-2">{{ $post->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ route('blog.rss') }}" class="flex items-center gap-2 text-xs text-ink-400 hover:text-brand transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 11a9 9 0 019 9M4 4a16 16 0 0116 16"/><circle cx="5" cy="19" r="1" fill="currentColor"/></svg>
                    Berlangganan RSS Feed
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
