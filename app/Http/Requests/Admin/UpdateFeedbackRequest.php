<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeedbackRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::exists('admin_module_options', 'value')->where('group', 'feedback_status')->where('status', true)],
            'admin_reply' => ['nullable', 'string', 'max:10000'],
        ];
    }
}
