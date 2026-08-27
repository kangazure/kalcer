<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class CoverageCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi tidak terdeteksi, mohon izinkan akses lokasi atau pilih titik di peta.',
            'longitude.required' => 'Lokasi tidak terdeteksi, mohon izinkan akses lokasi atau pilih titik di peta.',
        ];
    }
}
