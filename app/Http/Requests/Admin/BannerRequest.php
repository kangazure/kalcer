<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'marketing']);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'position' => ['required', Rule::in(['home_hero', 'sidebar', 'popup', 'top_bar', 'footer'])],
            'image_url' => ['required', 'url'],
            'image_url_mobile' => ['nullable', 'url'],
            'link_url' => ['nullable', 'url'],
            'link_target' => ['nullable', Rule::in(['_self', '_blank'])],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul banner wajib diisi.',
            'image_url.required' => 'Gambar banner wajib diunggah.',
        ];
    }
}
