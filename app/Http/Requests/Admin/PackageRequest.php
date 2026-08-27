<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'marketing']);
    }

    public function rules(): array
    {
        $packageId = $this->route('package')?->id;

        return [
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'category' => ['required', Rule::in(['home', 'business', 'dedicated', 'metro_ethernet', 'enterprise'])],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('packages', 'slug')->ignore($packageId)],
            'speed_mbps_download' => ['required', 'integer', 'min:1'],
            'speed_mbps_upload' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_promo' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'billing_cycle' => ['required', Rule::in(['monthly', 'quarterly', 'semiannual', 'annual'])],
            'is_unlimited' => ['boolean'],
            'fup_gb' => ['nullable', 'integer', 'min:1'],
            'installation_fee' => ['nullable', 'numeric', 'min:0'],
            'features' => ['nullable', 'array'],
            'is_popular' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket wajib diisi.',
            'price_promo.lt' => 'Harga promo harus lebih kecil dari harga normal.',
        ];
    }
}
