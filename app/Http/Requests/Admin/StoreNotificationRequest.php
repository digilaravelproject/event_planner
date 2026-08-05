<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool { return auth('admin')->check(); }
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:10000'],
            'notification_type' => ['required', Rule::exists('admin_module_options', 'value')->where('group', 'notification_type')->where('status', true)],
            'recipient_scope' => ['required', Rule::in(['single', 'multiple', 'all'])],
            'users' => [Rule::requiredIf(fn () => $this->input('recipient_scope') !== 'all'), 'array'],
            'users.*' => ['integer', 'distinct', 'exists:users,id'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'sent'])],
            'schedule_at' => ['nullable', 'date', 'after_or_equal:now'],
        ];
    }
}
