<?php

namespace App\Http\Requests\Admin;

use App\Services\RichTextSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug((string) ($this->input('slug') ?: $this->input('title'))),
            'description' => app(RichTextSanitizer::class)->sanitize($this->input('description')),
            'status' => $this->boolean('status'),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('pages', 'slug')->ignore($this->route('page')?->id)],
            'description' => ['required', 'string', 'max:100000'],
            'status' => ['boolean'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if (trim(strip_tags((string) $this->input('description'))) === '') {
                $validator->errors()->add('description', 'The page description must contain text.');
            }
        }];
    }
}
