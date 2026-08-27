@extends('layouts.admin')
@section('page_title', 'Media Manager')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Media Manager</span>@endsection

@section('content')
<div x-data="{ uploading: false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Media Manager</h1><p class="text-sm text-ink-500 mt-0.5">{{ $media->total() }} file tersimpan</p></div>
        <label class="btn-primary btn-sm cursor-pointer">
            <svg x-show="!uploading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <svg x-show="uploading" x-cloak class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span x-text="uploading ? 'Mengunggah...' : 'Upload Media'"></span>
            <input type="file" class="hidden" accept="image/*" multiple
                   @change="
                     uploading = true;
                     const formData = new FormData();
                     for (const f of $event.target.files) formData.append('files[]', f);
                     fetch('{{ route('admin.media.upload') }}', { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}, body: formData })
                       .then(() => window.location.reload())
                       .catch(() => uploading = false);
                   ">
        </label>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3">
        <select name="type" onchange="this.form.submit()" class="form-select text-sm w-40">
            <option value="">Semua Tipe</option>
            @foreach(['image'=>'Gambar','video'=>'Video','document'=>'Dokumen','audio'=>'Audio'] as $val=>$label)
                <option value="{{ $val }}" {{ request('type')===$val?'selected':'' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
        @forelse($media as $item)
            <div class="group relative bg-white dark:bg-ink-900 rounded-xl border border-ink-100 dark:border-ink-800 overflow-hidden aspect-square">
                @if($item->type === 'image')
                    <img src="{{ $item->public_url }}" alt="{{ $item->alt_text ?? $item->original_name }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-ink-50 dark:bg-ink-800">
                        <svg class="w-8 h-8 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-ink-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-2">
                    <button onclick="navigator.clipboard.writeText('{{ $item->public_url }}'); this.textContent='Disalin!'" class="text-white text-xs bg-white/20 hover:bg-white/30 px-2 py-1 rounded-lg backdrop-blur-sm w-full">
                        Salin URL
                    </button>
                    <form method="POST" action="{{ route('admin.media.destroy', $item->id) }}" class="w-full">
                        @csrf @method('DELETE')
                        <button type="submit" data-confirm-delete="Hapus file ini?" class="text-white text-xs bg-red-500/80 hover:bg-red-500 px-2 py-1 rounded-lg w-full">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full"><x-admin.empty-state title="Belum ada file media" description="Upload gambar pertama Anda menggunakan tombol di atas" /></div>
        @endforelse
    </div>

    <x-admin.pagination :paginator="$media" />
</div>
@endsection
