<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'marketing']);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'image_url' => ['required', 'url'],
            'video_url' => ['nullable', 'url'],
            'cta_label' => ['nullable', 'string', 'max:60'],
            'cta_url' => ['nullable', 'url'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul slide wajib diisi.',
            'image_url.required' => 'Gambar slide wajib diunggah.',
        ];
    }
}
