<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'content' => ['required', 'string', 'min:3', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ];

        if (! $this->user()) {
            $rules['guest_name'] = ['required', 'string', 'max:100'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.min' => 'Komentar terlalu singkat.',
            'guest_name.required' => 'Nama wajib diisi.',
            'guest_email.required' => 'Email wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['content' => strip_tags((string) $this->input('content'))]);
    }
}
