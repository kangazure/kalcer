<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin', 'editor', 'marketing']);
    }

    public function rules(): array
    {
        $portfolioId = $this->route('portfolio')?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('portfolio', 'slug')->ignore($portfolioId)],
            'client_name' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:150'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url'],
            'result_metric_label' => ['nullable', 'string', 'max:100'],
            'result_metric_value' => ['nullable', 'string', 'max:50'],
            'project_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return ['title.required' => 'Judul portfolio wajib diisi.'];
    }
}
