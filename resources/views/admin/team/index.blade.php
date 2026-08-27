@extends('layouts.admin')
@section('page_title', 'Tim')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Tim</span>@endsection

@section('content')
<div x-data="{ modalOpen: false, editing: null, form: { name:'', position:'', department:'', photo_url:'', bio:'', is_management: false, is_active: true } }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Anggota Tim</h1><p class="text-sm text-ink-500 mt-0.5">{{ $team->count() }} anggota</p></div>
        <button @click="modalOpen = true; editing = null; form = { name:'', position:'', department:'', photo_url:'', bio:'', is_management: false, is_active: true }" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Anggota Baru
        </button>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($team as $member)
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5 text-center">
                <div class="w-16 h-16 rounded-full bg-ink-100 dark:bg-ink-800 mx-auto mb-3 overflow-hidden flex items-center justify-center">
                    @if($member->photo_url)<img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else<span class="text-lg font-bold text-ink-400">{{ substr($member->name,0,1) }}</span>@endif
                </div>
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm">{{ $member->name }}</h3>
                <p class="text-xs text-ink-500 mb-1">{{ $member->position }}</p>
                @if($member->is_management)<span class="badge-brand text-[10px] mb-3 inline-block">Manajemen</span>@endif
                <div class="flex gap-1 mt-2">
                    <button @click="modalOpen = true; editing = {{ $member->id }}; form = { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position) }}', department: '{{ addslashes($member->department ?? '') }}', photo_url: '{{ $member->photo_url ?? '' }}', bio: '{{ addslashes($member->bio ?? '') }}', is_management: {{ $member->is_management ? 'true':'false' }}, is_active: {{ $member->is_active ? 'true':'false' }} }" class="flex-1 py-1.5 text-xs text-brand border border-brand/30 rounded-lg hover:bg-brand/5">Edit</button>
                    <form method="POST" action="{{ route('admin.team.destroy', $member->id) }}" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" data-confirm-delete="Hapus anggota '{{ $member->name }}'?" class="w-full py-1.5 text-xs text-red-500 border border-red-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full"><x-admin.empty-state title="Belum ada anggota tim" /></div>
        @endforelse
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4" x-text="editing ? 'Edit Anggota' : 'Anggota Baru'"></h2>
            <form :action="editing ? `/admin/team/${editing}` : '{{ route('admin.team.store') }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div class="space-y-3">
                    <div><label class="form-label">Nama</label><input type="text" name="name" x-model="form.name" required class="form-input text-sm"></div>
                    <div><label class="form-label">Jabatan</label><input type="text" name="position" x-model="form.position" required class="form-input text-sm"></div>
                    <div><label class="form-label">Departemen</label><input type="text" name="department" x-model="form.department" class="form-input text-sm"></div>
                    <div><label class="form-label">Foto URL</label><input type="url" name="photo_url" x-model="form.photo_url" class="form-input text-sm"></div>
                    <div><label class="form-label">Bio Singkat</label><textarea name="bio" x-model="form.bio" rows="2" class="form-textarea text-sm"></textarea></div>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_management" value="1" x-model="form.is_management" class="rounded border-ink-300 text-brand"><span class="text-sm">Manajemen</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-ink-300 text-brand"><span class="text-sm">Aktif</span></label>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <button type="button" @click="modalOpen = false" class="btn-ghost btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
