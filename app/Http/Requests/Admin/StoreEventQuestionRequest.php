<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $mappedValues = array_values(array_filter(array_map('strval', (array) $this->input('vendor_attribute_values', [])), fn (string $value): bool => $value !== ''));
        $this->merge([
            'is_required' => $this->boolean('is_required'),
            'status' => $this->boolean('status'),
            'options' => $mappedValues !== []
                ? $mappedValues
                : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $this->input('options_text'))))),
            'vendor_attribute_key' => $this->filled('vendor_attribute_key') ? trim((string) $this->input('vendor_attribute_key')) : null,
            'vendor_attribute_values' => $mappedValues,
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('event_question')?->id;

        return [
            'question' => ['required', 'string', 'max:500'],
            'question_code' => ['required', 'alpha_dash', 'max:100', Rule::unique('event_requirement_questions', 'question_code')->ignore($id)],
            'question_type' => ['required', Rule::exists('admin_module_options', 'value')->where('group', 'question_type')->where('status', true)],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'options' => [Rule::requiredIf(fn () => in_array($this->input('question_type'), ['dropdown', 'radio', 'checkbox'], true)), 'array'],
            'options.*' => ['string', 'max:255', 'distinct'],
            'vendor_attribute_key' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/'],
            'vendor_attribute_values' => [Rule::requiredIf(fn () => $this->filled('vendor_attribute_key')), 'array', 'max:500'],
            'vendor_attribute_values.*' => ['string', 'max:255', 'distinct'],
            'is_required' => ['boolean'],
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'status' => ['boolean'],
        ];
    }
}
