<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAiConfigurationRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }
    public function rules(): array
    {
        return [
            'openai_api_key' => ['nullable', 'string', 'max:500'],
            'openai_model' => ['required', Rule::exists('admin_module_options', 'value')->where('group', 'ai_model')->where('status', true)],
            'ai_prompt_template' => ['nullable', 'string', 'max:30000'],
            'status' => ['required', 'boolean'],
        ];
    }
}
