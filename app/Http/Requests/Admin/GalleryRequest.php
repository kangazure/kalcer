<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'editor', 'marketing']);
    }

    public function rules(): array
    {
        $galleryId = $this->route('gallery')?->id;

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('gallery', 'slug')->ignore($galleryId)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url'],
            'category' => ['nullable', 'string', 'max:60'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return ['title.required' => 'Judul galeri wajib diisi.'];
    }
}
