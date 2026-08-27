@extends('layouts.admin')
@section('page_title', isset($user) ? 'Edit Pengguna' : 'Pengguna Baru')
@section('breadcrumb')<a href="{{ route('admin.users.index') }}" class="text-ink-400 hover:text-brand">Pengguna</a><span class="text-ink-400 mx-2" aria-hidden="true">/</span><span class="text-ink-700 dark:text-ink-300">{{ isset($user) ? 'Edit' : 'Baru' }}</span>@endsection

@section('content')
<form method="POST" action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" class="max-w-xl">
    @csrf
    @if(isset($user)) @method('PUT') @endif
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
        <div><label class="form-label">Nama Lengkap</label><input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="form-input text-sm"></div>
        <div><label class="form-label">Nomor Telepon</label><input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-input text-sm"></div>
        <div>
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select text-sm" required>
                <option value="">Pilih role...</option>
                @foreach($roles ?? [] as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select text-sm">
                @foreach(['active'=>'Aktif','inactive'=>'Nonaktif','suspended'=>'Suspended','pending'=>'Pending'] as $val=>$label)
                    <option value="{{ $val }}" {{ old('status', $user->status ?? 'active') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">{{ isset($user) ? 'Password Baru (kosongkan jika tidak diubah)' : 'Password' }}</label>
            <input type="password" name="password" {{ isset($user) ? '' : 'required' }} minlength="8" class="form-input text-sm">
        </div>
        <div>
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" minlength="8" class="form-input text-sm">
        </div>
    </div>
    <div class="flex gap-2 mt-5">
        <button type="submit" class="btn-primary btn-sm">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn-ghost btn-sm">Batal</a>
    </div>
</form>
@endsection
