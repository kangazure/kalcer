@extends('layouts.admin')
@section('page_title', 'Detail Pesan')
@section('breadcrumb')
    <a href="{{ route('admin.contact.index') }}" class="text-ink-400 hover:text-brand">Pesan Kontak</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">#{{ $contact->id }}</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="font-bold text-ink-900 dark:text-white">{{ $contact->name }}</h1>
                <p class="text-sm text-ink-500 mt-0.5">{{ $contact->email }} · {{ $contact->phone }}</p>
            </div>
            <span class="badge text-xs {{ match($contact->status){'new'=>'badge-brand','in_progress'=>'badge-amber','resolved'=>'badge-green',default=>'bg-ink-100 text-ink-400'} }}">{{ ucfirst(str_replace('_',' ',$contact->status)) }}</span>
        </div>
        @if($contact->subject)<p class="font-medium text-ink-700 dark:text-ink-300 mb-2">{{ $contact->subject }}</p>@endif
        <div class="bg-ink-50 dark:bg-ink-800 rounded-xl p-4 text-sm text-ink-700 dark:text-ink-300 mb-4">{{ $contact->message }}</div>
        <div class="grid sm:grid-cols-2 gap-3 text-sm">
            <div><span class="text-ink-400">Sumber:</span> <span class="text-ink-700 dark:text-ink-300">{{ $contact->source }}</span></div>
            <div><span class="text-ink-400">Dikirim:</span> <span class="text-ink-700 dark:text-ink-300">{{ $contact->created_at->format('d M Y, H:i') }}</span></div>
            @if($contact->address)<div class="sm:col-span-2"><span class="text-ink-400">Alamat:</span> <span class="text-ink-700 dark:text-ink-300">{{ $contact->address }}</span></div>@endif
        </div>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-6">
        <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">Update Status</h2>
        <form method="POST" action="{{ route('admin.contact.update', $contact->id) }}">
            @csrf @method('PUT')
            <div class="space-y-3">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select text-sm">
                        @foreach(['new'=>'Baru','in_progress'=>'Diproses','resolved'=>'Selesai','closed'=>'Ditutup','spam'=>'Spam'] as $val=>$label)
                            <option value="{{ $val }}" {{ $contact->status===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Catatan Internal</label><textarea name="notes" rows="3" class="form-textarea text-sm">{{ $contact->notes }}</textarea></div>
            </div>
            <button type="submit" class="btn-primary btn-sm mt-4">Simpan Perubahan</button>
        </form>
    </div>

    <a href="mailto:{{ $contact->email }}" class="btn-outline btn-sm inline-flex">Balas via Email</a>
</div>
@endsection
