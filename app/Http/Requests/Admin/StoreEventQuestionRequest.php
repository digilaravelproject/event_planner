<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventQuestionRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->boolean('is_required'),
            'status' => $this->boolean('status'),
            'options' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $this->input('options_text'))))),
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
            'is_required' => ['boolean'],
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'status' => ['boolean'],
        ];
    }
}
