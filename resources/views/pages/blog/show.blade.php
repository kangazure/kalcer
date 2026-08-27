@extends('layouts.app')
@push('seo_title'){{ $post->seo_title ?? $post->title }} — Blog JTS@endpush
@push('seo_description'){{ $post->seo_description ?? $post->excerpt }}@endpush
@push('og_type')article@endpush
@push('og_image'){{ $post->og_image_url ?? $post->cover_image_url ?? asset('images/og/default-og.jpg') }}@endpush
@push('canonical_url')<link rel="canonical" href="{{ $post->canonical_url ?? route('blog.show', $post->slug) }}">@endpush
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": @json($post->title),
    "datePublished": "{{ $post->published_at?->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at?->toIso8601String() }}",
    "author": { "@type": "Person", "name": @json($post->author?->name ?? 'PT Jaringan Teknologi Sejahtera') }
}
</script>
@endpush

@section('content')
<article x-data="{
        liked: false, likeCount: {{ $post->like_count }},
        bookmarked: false,
        toggleLike() {
            fetch('{{ route('blog.like', $post->id) }}', { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content} })
                .then(r => r.json()).then(d => { this.liked = d.liked; this.likeCount += d.liked ? 1 : -1; });
        },
        toggleBookmark() {
            fetch('{{ route('blog.bookmark', $post->id) }}', { method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content} })
                .then(r => r.json()).then(d => { this.bookmarked = d.bookmarked; })
                .catch(() => window.location.href = '{{ route('admin.login') }}');
        }
    }">

    <section class="page-hero py-16 lg:py-20">
        <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-ink-500 mb-6" aria-label="Breadcrumb" data-aos="fade-down">
                <a href="{{ route('blog.index') }}" class="hover:text-brand">Blog</a>
                @if($post->category)
                    <span class="mx-2" aria-hidden="true">/</span>
                    <a href="{{ route('blog.index', ['category_slug' => $post->category->slug]) }}" class="hover:text-brand">{{ $post->category->name }}</a>
                @endif
            </nav>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight mb-6" data-aos="fade-up">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-ink-400" data-aos="fade-up" data-aos-delay="100">
                <div class="w-9 h-9 rounded-full bg-brand/20 flex items-center justify-center shrink-0 text-brand font-bold text-sm">{{ substr($post->author?->name ?? 'J', 0, 1) }}</div>
                <div>
                    <span class="text-ink-200 font-medium block">{{ $post->author?->name ?? 'Tim JTS' }}</span>
                    <span class="text-xs">{{ $post->published_at?->format('d M Y') }} · {{ $post->reading_time_minutes }} menit baca · {{ number_format($post->view_count) }} views</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white dark:bg-ink-950">
        <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">

            @if($post->cover_image_url)
                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-64 sm:h-96 object-cover rounded-2xl mb-10" data-aos="fade-up">
            @endif

            {{-- Toolbar like/bookmark/share --}}
            <div class="flex items-center justify-between py-4 border-y border-ink-100 dark:border-ink-800 mb-10 sticky top-16 bg-white/90 dark:bg-ink-950/90 backdrop-blur-sm z-10">
                <div class="flex items-center gap-4">
                    <button @click="toggleLike()" class="flex items-center gap-1.5 text-sm" :class="liked ? 'text-brand-red' : 'text-ink-500 hover:text-brand-red'">
                        <svg class="w-5 h-5" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span x-text="likeCount"></span>
                    </button>
                    <button @click="toggleBookmark()" class="text-ink-500 hover:text-brand transition-colors" :class="bookmarked && 'text-brand'" aria-label="Simpan artikel">
                        <svg class="w-5 h-5" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <a href="https://wa.me/?text={{ urlencode($post->title.' - '.route('blog.show', $post->slug)) }}" target="_blank" rel="noopener noreferrer" class="text-ink-400 hover:text-green-500 transition-colors" aria-label="Bagikan via WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                    </a>
                </div>
            </div>

            <div class="blog-content prose-jts" data-aos="fade-up">
                {!! $post->content_html ?? \Illuminate\Support\Str::markdown($post->content) !!}
            </div>

            @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-10 pt-8 border-t border-ink-100 dark:border-ink-800">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag_slug' => $tag->slug]) }}" class="tag-pill">{{ $tag->name }}</a>
                @endforeach
            </div>
            @endif

            {{-- Author box --}}
            <div class="flex gap-4 items-start mt-10 p-6 bg-surface-soft dark:bg-ink-900 rounded-2xl">
                <div class="w-14 h-14 rounded-full bg-brand/20 flex items-center justify-center shrink-0 text-brand font-bold text-lg">{{ substr($post->author?->name ?? 'J', 0, 1) }}</div>
                <div>
                    <h3 class="font-semibold text-ink-900 dark:text-white">{{ $post->author?->name ?? 'Tim JTS' }}</h3>
                    <p class="text-sm text-ink-500 dark:text-ink-400 mt-1">{{ $post->author?->bio ?? 'Kontributor blog PT Jaringan Teknologi Sejahtera.' }}</p>
                </div>
            </div>

            {{-- Komentar --}}
            <div class="mt-14" id="comments">
                <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-6">Komentar ({{ $post->approvedComments->count() ?? 0 }})</h2>

                <form x-data="{ content: '', guest_name: '', guest_email: '', submitting: false, done: false }"
                      @submit.prevent="
                        submitting = true;
                        fetch('{{ route('blog.comment', $post->id) }}', {
                            method: 'POST',
                            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                            body: JSON.stringify({content, guest_name, guest_email})
                        }).then(r => r.json()).then(() => { done = true; submitting = false; content=''; }).catch(() => submitting = false);
                      "
                      class="mb-10 p-6 bg-surface-soft dark:bg-ink-900 rounded-2xl">
                    <div x-show="!done">
                        @guest
                        <div class="grid sm:grid-cols-2 gap-3 mb-3">
                            <input type="text" x-model="guest_name" placeholder="Nama Anda" required class="form-input text-sm">
                            <input type="email" x-model="guest_email" placeholder="Email Anda" required class="form-input text-sm">
                        </div>
                        @endguest
                        <textarea x-model="content" rows="3" placeholder="Tulis komentar Anda..." required class="form-textarea text-sm mb-3"></textarea>
                        <button type="submit" :disabled="submitting" class="btn-primary btn-sm text-sm">
                            <span x-text="submitting ? 'Mengirim...' : 'Kirim Komentar'"></span>
                        </button>
                    </div>
                    <div x-show="done" x-cloak class="alert-success">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Komentar Anda telah dikirim dan menunggu moderasi.
                    </div>
                </form>

                <div class="space-y-6">
                    @forelse($post->approvedComments ?? [] as $comment)
                        <div class="flex gap-3">
                            <div class="w-9 h-9 rounded-full bg-ink-100 dark:bg-ink-800 flex items-center justify-center shrink-0 text-sm font-bold text-ink-500">{{ substr($comment->user?->name ?? $comment->guest_name, 0, 1) }}</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $comment->user?->name ?? $comment->guest_name }}</span>
                                    <span class="text-xs text-ink-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-ink-600 dark:text-ink-400">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-400 text-center py-6">Jadilah yang pertama berkomentar.</p>
                    @endforelse
                </div>
            </div>

            {{-- Related --}}
            @if($related->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-ink-100 dark:border-ink-800">
                <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-6">Artikel Terkait</h2>
                <div class="grid sm:grid-cols-2 gap-6">
                    @foreach($related as $relatedPost)
                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="group flex gap-4 hover-card">
                            <div class="w-24 h-20 rounded-xl overflow-hidden shrink-0 bg-brand/10">
                                @if($relatedPost->cover_image_url)
                                    <img src="{{ $relatedPost->cover_image_url }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm text-ink-900 dark:text-white line-clamp-2 group-hover:text-brand transition-colors">{{ $relatedPost->title }}</h3>
                                <p class="text-xs text-ink-400 mt-1">{{ $relatedPost->published_at?->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
</article>
@endsection
