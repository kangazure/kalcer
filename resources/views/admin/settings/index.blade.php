@extends('layouts.admin')
@section('page_title', 'Pengaturan Website')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Pengaturan</span>@endsection

@section('content')
<div x-data="{ activeTab: '{{ $group ?? 'general' }}' }" class="space-y-5">
    <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Pengaturan Website</h1><p class="text-sm text-ink-500 mt-0.5">Kelola konfigurasi global situs</p></div>

    <div class="flex gap-2 border-b border-ink-200 dark:border-ink-800 overflow-x-auto">
        @foreach(['general'=>'Umum','seo'=>'SEO','social'=>'Media Sosial','smtp'=>'Email/SMTP','integration'=>'Integrasi'] as $key=>$label)
            <a href="{{ route('admin.settings.index', $key) }}"
               class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap {{ ($group ?? 'general') === $key ? 'border-brand text-brand' : 'border-transparent text-ink-500 hover:text-ink-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.settings.update', $group ?? 'general') }}" class="max-w-2xl">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
            @forelse($settings ?? [] as $key => $setting)
                <div>
                    <label class="form-label">{{ $setting['label'] ?? ucwords(str_replace('_', ' ', $key)) }}</label>
                    @if(str_contains($key, 'description') || str_contains($key, 'address'))
                        <textarea name="settings[{{ $key }}]" rows="3" class="form-textarea text-sm">{{ old("settings.$key", $setting['value'] ?? '') }}</textarea>
                    @else
                        <input type="text" name="settings[{{ $key }}]" value="{{ old("settings.$key", $setting['value'] ?? '') }}" class="form-input text-sm">
                    @endif
                    @if(!empty($setting['description']))
                        <p class="text-xs text-ink-400 mt-1">{{ $setting['description'] }}</p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-ink-400">Tidak ada pengaturan pada grup ini.</p>
            @endforelse
        </div>
        <button type="submit" class="btn-primary btn-sm mt-4">Simpan Pengaturan</button>
    </form>
</div>
@endsection
