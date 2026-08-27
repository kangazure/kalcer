<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'marketing']);
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_role' => ['nullable', 'string', 'max:150'],
            'customer_photo_url' => ['nullable', 'url'],
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['required', 'string', 'max:2000'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'content.required' => 'Isi testimoni wajib diisi.',
        ];
    }
}
