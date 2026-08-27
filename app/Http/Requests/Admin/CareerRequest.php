<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRoleSlug(['super_admin', 'admin']);
    }

    public function rules(): array
    {
        $careerId = $this->route('career')?->id;

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('career', 'slug')->ignore($careerId)],
            'department' => ['nullable', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:150'],
            'job_type' => ['required', Rule::in(['full_time', 'part_time', 'internship', 'contract', 'remote'])],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'array'],
            'responsibilities' => ['nullable', 'array'],
            'benefits' => ['nullable', 'array'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'salary_is_negotiable' => ['boolean'],
            'vacancy_count' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
            'closes_at' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul lowongan wajib diisi.',
            'description.required' => 'Deskripsi pekerjaan wajib diisi.',
        ];
    }
}
