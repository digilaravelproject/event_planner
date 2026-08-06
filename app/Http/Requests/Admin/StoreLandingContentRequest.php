<?php

namespace App\Http\Requests\Admin;

use App\Models\LandingContent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLandingContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status'),
            'meta' => array_filter([
                'eyebrow' => $this->input('eyebrow'),
                'footer' => $this->input('footer'),
                'side' => $this->input('side'),
                'rating' => $this->input('rating'),
                'date' => $this->input('date'),
            ], fn ($value) => $value !== null && $value !== ''),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'max:5120'],
            'existing_image' => ['nullable', 'string', 'max:2048'],
            'meta' => ['nullable', 'array'],
            'meta.eyebrow' => ['nullable', 'string', 'max:100'],
            'meta.footer' => ['nullable', 'string', 'max:150'],
            'meta.side' => ['nullable', Rule::in(['manual', 'ai'])],
            'meta.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'meta.date' => ['nullable', 'string', 'max:50'],
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'status' => ['boolean'],
        ];
    }
}
