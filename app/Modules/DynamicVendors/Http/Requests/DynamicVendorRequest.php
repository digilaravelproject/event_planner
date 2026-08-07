<?php

namespace App\Modules\DynamicVendors\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

abstract class DynamicVendorRequest extends FormRequest
{
    public const TYPES = [
        'text', 'textarea', 'number', 'currency', 'dropdown', 'multi_select',
        'checkbox', 'radio', 'date', 'time', 'datetime', 'url', 'email',
        'phone', 'boolean', 'color', 'image', 'file', 'video', 'gps',
        'rich_text', 'json',
    ];

    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'draft', 'archived'])],
            'attributes' => ['nullable', 'array'],
            'attributes.*.id' => ['nullable', 'uuid'],
            'attributes.*.label' => ['required', 'string', 'max:255'],
            'attributes.*.type' => ['required', Rule::in(self::TYPES)],
            'attributes.*.value' => ['nullable'],
            'attributes.*.required' => ['nullable', 'boolean'],
            'attributes.*.min_length' => ['nullable', 'integer', 'min:0'],
            'attributes.*.max_length' => ['nullable', 'integer', 'gte:attributes.*.min_length'],
            'attributes.*.min_value' => ['nullable', 'numeric'],
            'attributes.*.max_value' => ['nullable', 'numeric'],
            'attributes.*.allowed_values' => ['nullable', 'string'],
            'attributes.*.default_value' => ['nullable'],
            'attribute_uploads' => ['nullable', 'array'],
            'attribute_uploads.*' => ['nullable', 'file', 'max:51200'],
            'attribute_images' => ['nullable', 'array'],
            'attribute_images.*' => ['nullable', 'array', 'max:20'],
            'attribute_images.*.*' => ['image', 'max:10240'],
            'existing_attribute_images' => ['nullable', 'array'],
            'existing_attribute_images.*' => ['nullable', 'array'],
            'existing_attribute_images.*.*' => ['string', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['string', 'max:2048'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string'],
            'offerings' => ['nullable', 'array'],
            'offerings.*.name' => ['nullable', 'string', 'max:255'],
            'offerings.*.category' => ['nullable', 'string', 'max:255'],
            'offerings.*.min_capacity' => ['nullable', 'integer', 'min:0'],
            'offerings.*.max_capacity' => ['nullable', 'integer', 'min:0'],
            'offerings.*.min_budget' => ['nullable', 'numeric', 'min:0'],
            'offerings.*.max_budget' => ['nullable', 'numeric', 'min:0'],
            'offerings.*.locations' => ['nullable', 'string'],
            'offerings.*.traditions' => ['nullable', 'string'],
            'offerings.*.notes' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            foreach ($this->input('attributes', []) as $index => $attribute) {
                $this->validateDynamicAttribute($validator, (int) $index, $attribute);
            }
        }];
    }

    private function validateDynamicAttribute(Validator $validator, int $index, array $attribute): void
    {
        $value = $attribute['value'] ?? null;
        $label = $attribute['label'] ?? 'Attribute';
        $type = $attribute['type'] ?? 'text';
        $upload = $this->file("attribute_uploads.$index");
        $empty = $upload === null && ($value === null || $value === '' || $value === []);

        if (filter_var($attribute['required'] ?? false, FILTER_VALIDATE_BOOL) && $empty) {
            $validator->errors()->add("attributes.$index.value", "$label is required.");

            return;
        }

        if ($empty || $upload !== null) {
            if ($upload !== null && $type === 'image' && ! str_starts_with((string) $upload->getMimeType(), 'image/')) {
                $validator->errors()->add("attribute_uploads.$index", "$label must be an image.");
            }
            if ($upload !== null && $type === 'video' && ! str_starts_with((string) $upload->getMimeType(), 'video/')) {
                $validator->errors()->add("attribute_uploads.$index", "$label must be a video.");
            }

            return;
        }

        $stringValue = is_array($value) ? implode(',', $value) : (string) $value;
        $length = mb_strlen($stringValue);
        if (isset($attribute['min_length']) && $attribute['min_length'] !== '' && $length < (int) $attribute['min_length']) {
            $validator->errors()->add("attributes.$index.value", "$label must contain at least {$attribute['min_length']} characters.");
        }
        if (isset($attribute['max_length']) && $attribute['max_length'] !== '' && $length > (int) $attribute['max_length']) {
            $validator->errors()->add("attributes.$index.value", "$label may not exceed {$attribute['max_length']} characters.");
        }
        if (in_array($type, ['number', 'currency'], true) && ! is_numeric($value)) {
            $validator->errors()->add("attributes.$index.value", "$label must be numeric.");
        }
        if (isset($attribute['min_value']) && $attribute['min_value'] !== '' && is_numeric($value) && $value < $attribute['min_value']) {
            $validator->errors()->add("attributes.$index.value", "$label must be at least {$attribute['min_value']}.");
        }
        if (isset($attribute['max_value']) && $attribute['max_value'] !== '' && is_numeric($value) && $value > $attribute['max_value']) {
            $validator->errors()->add("attributes.$index.value", "$label may not exceed {$attribute['max_value']}.");
        }
        if ($type === 'email' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid email address.");
        }
        if ($type === 'url' && filter_var($value, FILTER_VALIDATE_URL) === false) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid URL.");
        }
        if ($type === 'date' && ! $this->hasFormat($stringValue, 'Y-m-d')) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid date (YYYY-MM-DD).");
        }
        if ($type === 'time' && ! $this->hasFormat($stringValue, 'H:i') && ! $this->hasFormat($stringValue, 'H:i:s')) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid time.");
        }
        if ($type === 'datetime' && strtotime($stringValue) === false) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid date and time.");
        }
        if ($type === 'color' && preg_match('/^#[0-9a-f]{6}$/i', $stringValue) !== 1) {
            $validator->errors()->add("attributes.$index.value", "$label must be a six-digit hex colour.");
        }
        if ($type === 'phone' && preg_match('/^[0-9+().\-\s]{6,30}$/', $stringValue) !== 1) {
            $validator->errors()->add("attributes.$index.value", "$label must be a valid phone number.");
        }
        if ($type === 'gps' && preg_match('/^-?\d{1,2}(?:\.\d+)?,\s*-?\d{1,3}(?:\.\d+)?$/', $stringValue) !== 1) {
            $validator->errors()->add("attributes.$index.value", "$label must use latitude, longitude format.");
        }
        $allowed = array_values(array_filter(array_map('trim', explode(',', (string) ($attribute['allowed_values'] ?? '')))));
        if ($allowed !== [] && in_array($type, ['dropdown', 'radio'], true) && ! in_array($stringValue, $allowed, true)) {
            $validator->errors()->add("attributes.$index.value", "$label must be one of its allowed values.");
        }
        if ($allowed !== [] && $type === 'multi_select') {
            $unknown = array_diff(array_map('trim', explode(',', $stringValue)), $allowed);
            if ($unknown !== []) {
                $validator->errors()->add("attributes.$index.value", "$label contains a value that is not allowed.");
            }
        }
        if ($type === 'json') {
            json_decode($stringValue, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $validator->errors()->add("attributes.$index.value", "$label must contain valid JSON.");
            }
        }
    }

    private function hasFormat(string $value, string $format): bool
    {
        $date = \DateTimeImmutable::createFromFormat('!'.$format, $value);

        return $date !== false && $date->format($format) === $value;
    }
}
