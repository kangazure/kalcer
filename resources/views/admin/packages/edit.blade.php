@extends('layouts.admin')
@section('page_title', isset($package) ? 'Edit Paket' : 'Paket Baru')
@section('breadcrumb')
    <a href="{{ route('admin.packages.index') }}" class="text-ink-400 hover:text-brand">Paket Internet</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span>
    <span class="text-ink-700 dark:text-ink-300">{{ isset($package) ? 'Edit' : 'Baru' }}</span>
@endsection

@section('content')
<form method="POST" action="{{ isset($package) ? route('admin.packages.update', $package->id) : route('admin.packages.store') }}"
      x-data="{ features: {{ json_encode(old('features', $package->features ?? [''])) }} }">
    @csrf
    @if(isset($package)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 space-y-4">
                <div><label class="form-label">Nama Paket</label><input type="text" name="name" value="{{ old('name', $package->name ?? '') }}" required class="form-input text-sm"></div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Layanan Terkait</label>
                        <select name="service_id" class="form-select text-sm">
                            <option value="">Tidak terkait</option>
                            @foreach($services ?? [] as $svc)
                                <option value="{{ $svc->id }}" {{ old('service_id', $package->service_id ?? '') == $svc->id ? 'selected' : '' }}>{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select text-sm">
                            @foreach(['home'=>'Internet Rumah','business'=>'Internet Bisnis','dedicated'=>'Dedicated','metro_ethernet'=>'Metro Ethernet','enterprise'=>'Enterprise'] as $val=>$label)
                                <option value="{{ $val }}" {{ old('category', $package->category ?? 'home') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div><label class="form-label">Kecepatan Download (Mbps)</label><input type="number" name="speed_mbps_download" value="{{ old('speed_mbps_download', $package->speed_mbps_download ?? '') }}" required class="form-input text-sm"></div>
                    <div><label class="form-label">Kecepatan Upload (Mbps)</label><input type="number" name="speed_mbps_upload" value="{{ old('speed_mbps_upload', $package->speed_mbps_upload ?? '') }}" required class="form-input text-sm"></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div><label class="form-label">Harga Normal (Rp)</label><input type="number" name="price" value="{{ old('price', $package->price ?? '') }}" required class="form-input text-sm"></div>
                    <div><label class="form-label">Harga Promo (Rp, opsional)</label><input type="number" name="price_promo" value="{{ old('price_promo', $package->price_promo ?? '') }}" class="form-input text-sm"></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div><label class="form-label">Biaya Instalasi (Rp)</label><input type="number" name="installation_fee" value="{{ old('installation_fee', $package->installation_fee ?? 0) }}" class="form-input text-sm"></div>
                    <div>
                        <label class="form-label">Siklus Tagihan</label>
                        <select name="billing_cycle" class="form-select text-sm">
                            @foreach(['monthly'=>'Bulanan','quarterly'=>'3 Bulan','semiannual'=>'6 Bulan','annual'=>'Tahunan'] as $val=>$label)
                                <option value="{{ $val }}" {{ old('billing_cycle', $package->billing_cycle ?? 'monthly') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="form-label mb-0">Fitur Paket</label>
                    <button type="button" @click="features.push('')" class="text-xs text-brand hover:underline">+ Tambah</button>
                </div>
                <template x-for="(f, i) in features" :key="i">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="`features[${i}]`" x-model="features[i]" class="form-input text-sm">
                        <button type="button" @click="features.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">✕</button>
                    </div>
                </template>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-200 dark:border-ink-700 p-6 sticky top-20">
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="is-unlimited" name="is_unlimited" value="1" {{ old('is_unlimited', $package->is_unlimited ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-unlimited" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Unlimited (tanpa FUP)</label>
                </div>
                <div class="mb-4"><label class="form-label text-xs">FUP (GB, jika bukan unlimited)</label><input type="number" name="fup_gb" value="{{ old('fup_gb', $package->fup_gb ?? '') }}" class="form-input text-sm"></div>
                <div class="flex items-center gap-2 mb-3">
                    <input type="checkbox" id="is-popular" name="is_popular" value="1" {{ old('is_popular', $package->is_popular ?? false) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-popular" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Tandai "Populer"</label>
                </div>
                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" id="is-active" name="is_active" value="1" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }} class="rounded border-ink-300 text-brand">
                    <label for="is-active" class="text-sm text-ink-700 dark:text-ink-300 cursor-pointer">Aktif</label>
                </div>
                <div class="mb-4"><label class="form-label text-xs">Urutan Tampil</label><input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" class="form-input text-sm"></div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <a href="{{ route('admin.packages.index') }}" class="btn-ghost btn-sm">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
