<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title>Blog PT Jaringan Teknologi Sejahtera</title>
    <link>{{ route('blog.index') }}</link>
    <atom:link href="{{ route('blog.rss') }}" rel="self" type="application/rss+xml" />
    <description>Artikel seputar teknologi internet, tips, dan berita PT Jaringan Teknologi Sejahtera (JTS)</description>
    <language>id-ID</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>

    @foreach($posts as $post)
    <item>
        <title>{{ $post->title }}</title>
        <link>{{ route('blog.show', $post->slug) }}</link>
        <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
        <pubDate>{{ ($post->published_at ?? $post->created_at)->toRssString() }}</pubDate>
        @if($post->category)
        <category>{{ $post->category->name }}</category>
        @endif
        <description><![CDATA[{{ $post->excerpt }}]]></description>
    </item>
    @endforeach
</channel>
</rss>
