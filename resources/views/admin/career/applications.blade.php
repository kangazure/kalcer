@extends('layouts.admin')
@section('page_title', 'Lamaran: '.$career->title)
@section('breadcrumb')
    <a href="{{ route('admin.career.index') }}" class="text-ink-400 hover:text-brand">Karir</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">Lamaran</span>
@endsection

@section('content')
<div class="space-y-5">
    <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Lamaran untuk: {{ $career->title }}</h1><p class="text-sm text-ink-500 mt-0.5">{{ $applications->count() }} pelamar</p></div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Kontak</th><th>CV</th><th>Status</th><th>Tanggal</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $app->full_name }}</td>
                        <td class="text-sm text-ink-500">{{ $app->email }}<br>{{ $app->phone }}</td>
                        <td>
                            @if($app->resume)
                                <a href="{{ route('admin.media.index') }}" class="text-brand text-xs hover:underline">Lihat CV</a>
                            @else
                                <span class="text-ink-400 text-xs">-</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.career.applications.status', $app->id) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-select text-xs">
                                    @foreach(['submitted'=>'Submitted','screening'=>'Screening','interview'=>'Interview','offered'=>'Offered','hired'=>'Hired','rejected'=>'Rejected'] as $val=>$label)
                                        <option value="{{ $val }}" {{ $app->status===$val?'selected':'' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="text-xs text-ink-400">{{ $app->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            @if($app->cover_letter)
                                <button onclick="alert('{{ addslashes(Str::limit($app->cover_letter, 500)) }}')" class="text-xs text-ink-500 hover:text-brand">Lihat Cover Letter</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada lamaran masuk" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
